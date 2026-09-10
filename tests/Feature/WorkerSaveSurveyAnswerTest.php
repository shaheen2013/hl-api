<?php

namespace Tests\Feature;

use App\User;
use App\Brand;
use App\Category;
use App\Hotel;
use App\Satisfaction;
use App\Question;
use App\Survey;
use App\SurveyQuestion;
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use App\QuestionBrand;
use App\QuestionText;
use App\SatisfactionAnswer;
use App\HotelSatisfaction;
use App\HotelReview;
use App\UserBrand;
use App\QuestionResponse;
use App\QuestionResponseText;
use Carbon\Carbon;
use App\Jobs\ProcessSaveSurveyAnswer;
use App\Types\Survey\SaveSurveyAnswersDataType;
use App\Events\Surveys\SurveyQuestionAnsweredEvent;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class WorkerSaveSurveyAnswerTest extends TestCase
{
    /*
        TODO: Add test to ensure that old events are being sent.
    */

    public function testSaveGenericSurveyAndReviewSentSuccessfully()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $user = factory(User::class)->create();

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id,
            'name' => 'survey_name'
        ]);

        factory(UserBrand::class)->create([
            'brand_id'  => $brand->id,
            'user_id'   => $user->id
        ]);

        factory(HotelSatisfaction::class)->create([
            'id_hotel'              => $hotel->id,
            'puntMin'               => 5,
            'review_average_score'  => 8
        ]);

        factory(HotelReview::class)->create([
            'id_hotel'      => $hotel->id,
            'ignoreRating'  => 0
        ]);

        $userSatisfaction = factory(Satisfaction::class)->create([
            'id_hotel' => $hotel->id,
            'id_usuario' => $user->id,
            'done' => 0,
        ]);

        $genericQuestion = QuestionBrand::whereNull('brand_id')->first();

        $userSurvey = factory(UserSurvey::class)->create([
            'survey_id'             => $survey->id,
            'brand_id'              => $brand->id,
            'user_id'               => $user->id,
            'user_satisfaction_id'  => $userSatisfaction->id,
            'review_sent'           => 0
        ]);

        $surveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id'     => $survey->id,
            'question_id'   => $genericQuestion->question_id,
            'active'        => 1,
            'required'      => 1
        ]);

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'brand_id'  => $brand->id,
            'survey_id' => $userSatisfaction->id,
            'user_id'   => $user->id,
            'score'     => 10,
            'comment'   => "Test comment 👏👏"
        ]);

        Event::fake();
        $data = new SaveSurveyAnswersDataType($request);
        $job = new ProcessSaveSurveyAnswer($data);
        $job->handle(
            app('App\Services\BrandService'),
            app('App\Services\Users\UserService'),
            app('App\Services\Connections\ConnectionHistoryService'),
            app('App\Repositories\Satisfactions\CustomizedSatisfactionAnswerRepository'),
            app('App\Services\Products\SatisfactionSurveyProductService'),
            app('App\Services\Users\UserBrandService'),
            app('App\Services\Visits\VisitService')
        );

        Event::assertDispatched(SurveyQuestionAnsweredEvent::class, function ($event) {
            return
                data_get($event, 'payload.survey.type') === "generic" &&
                !data_get($event, 'payload.survey.summary') &&
                data_get($event, 'payload.survey.score') == 10 &&
                data_get($event, 'payload.survey.comment') == "Test comment" &&
                data_get($event, 'payload.survey.generic.score') == 10 &&
                data_get($event, 'payload.survey.generic.comment') == "Test comment";
        });

        $userSurveyQuestionAnswer = UserSurveyQuestionAnswer::where([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $surveyQuestion->id
        ])->first();

        // Test data saved on new system tables
        $this->assertEquals($userSurveyQuestionAnswer->answer, 10);
        $this->assertEquals($userSurveyQuestionAnswer->comment, "Test comment");

        // Test data saved on old system tables
        $userSatisfaction = $userSatisfaction->fresh();
        $this->assertEquals($userSatisfaction->done, 1);
        $this->assertEquals($userSatisfaction->puntuacion, 10);
        $this->assertEquals($userSatisfaction->comentario, "Test comment");

        // Test review email sent
        $this->assertEquals($userSurvey->fresh()->review_sent, 1);
        $this->assertEquals($userSatisfaction->review_send, 1);
    }

    public function testSaveCustomizedSurveySuccessfully()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $user = factory(User::class)->create();

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id,
            'name' => 'survey_name'
        ]);

        factory(UserBrand::class)->create([
            'brand_id'  => $brand->id,
            'user_id'   => $user->id
        ]);

        $category = factory(Category::class)->create(['brand_id' => $brand->id]);
        $question = factory(Question::class)->create(['category_id' => $category->id]);
        $disabledQuestion = factory(Question::class)->create(['category_id' => $category->id]);

        $userSatisfaction = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'done'          => 1,
        ]);

        $genericQuestion = QuestionBrand::whereNull('brand_id')->first();

        $userSurvey = factory(UserSurvey::class)->create([
            'survey_id'             => $survey->id,
            'brand_id'              => $brand->id,
            'user_id'               => $user->id,
            'user_satisfaction_id'  => $userSatisfaction->id,
            'review_sent'           => 0
        ]);

        $genericSurveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id'     => $survey->id,
            'question_id'   => $genericQuestion->question_id,
            'active'        => 1,
            'required'      => 1
        ]);

        factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $question->id,
            'required' => 1,
            'active' => 1
        ]);
        $surveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id'     => $survey->id,
            'question_id'   => $question->id,
            'active'        => 1,
            'required'      => 1
        ]);
        factory(QuestionText::class)->create([
            'question_id' => $question->id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $disabledQuestion->id,
            'required' => 1,
            'active' => 0
        ]);
        factory(SurveyQuestion::class)->create([
            'survey_id'     => $survey->id,
            'question_id'   => $disabledQuestion->id,
            'active'        => 0,
            'required'      => 1
        ]);
        factory(QuestionText::class)->create([
            'question_id' => $disabledQuestion->id,
            'lang_value' => 'en',
            'text' => "Disabled Question text test"
        ]);

        $genericResponse = factory(UserSurveyQuestionAnswer::class)->create([
            'survey_question_id'    => $genericSurveyQuestion->id,
            'user_survey_id'        => $userSurvey->id,
        ]);

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'brand_id'      => $brand->id,
            'survey_id'     => $userSatisfaction->id,
            'question_id'   => $question->id,
            'user_id'       => $user->id,
            'score'         => 10,
            'comment'       => "Test comment 👏👏"
        ]);

        Event::fake();
        $data = new SaveSurveyAnswersDataType($request);
        $job = new ProcessSaveSurveyAnswer($data);
        $job->handle(
            app('App\Services\BrandService'),
            app('App\Services\Users\UserService'),
            app('App\Services\Connections\ConnectionHistoryService'),
            app('App\Repositories\Satisfactions\CustomizedSatisfactionAnswerRepository'),
            app('App\Services\Products\SatisfactionSurveyProductService'),
            app('App\Services\Users\UserBrandService'),
            app('App\Services\Visits\VisitService')
        );

        Event::assertDispatched(SurveyQuestionAnsweredEvent::class, function ($event) use ($genericResponse) {
            $this->assertStringContainsString("Question text test", json_encode(data_get($event, 'payload.survey.summary')));
            $this->assertStringNotContainsString("Disabled Question text test", json_encode(data_get($event, 'payload.survey.summary')));

            return
                data_get($event, 'payload.survey.type') === "customized" &&
                data_get($event, 'payload.survey.summary') &&
                data_get($event, 'payload.survey.score') == 10 &&
                data_get($event, 'payload.survey.comment') == "Test comment" &&
                data_get($event, 'payload.survey.generic.score') == $genericResponse->answer &&
                data_get($event, 'payload.survey.generic.comment') == $genericResponse->comment;
        });

        $userSurveyQuestionAnswer = UserSurveyQuestionAnswer::where([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $surveyQuestion->id
        ])->first();

        // Test data saved on new system tables
        $this->assertEquals($userSurveyQuestionAnswer->answer, 10);
        $this->assertEquals($userSurveyQuestionAnswer->comment, "Test comment");

        // Test data saved on old system tables
        $customizedSatisfaction = SatisfactionAnswer::where([
            'survey_question_id'    => $question->id,
            'user_satisfaction_id'  => $userSatisfaction->id
        ])->first();

        $this->assertEquals($customizedSatisfaction->answer, 10);
        $this->assertEquals($customizedSatisfaction->comment, "Test comment");
    }

    public function testSaveMultiresponseCustomizedSurveySuccessfully()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $user = factory(User::class)->create();

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id,
            'name' => 'survey_name'
        ]);

        factory(UserBrand::class)->create([
            'brand_id'  => $brand->id,
            'user_id'   => $user->id
        ]);

        $category = factory(Category::class)->create(['brand_id' => $brand->id]);
        $question = factory(Question::class)->create(['category_id' => $category->id]);

        $userSatisfaction = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'done'          => 1,
        ]);

        $genericQuestion = QuestionBrand::whereNull('brand_id')->first();

        $userSurvey = factory(UserSurvey::class)->create([
            'survey_id'             => $survey->id,
            'brand_id'              => $brand->id,
            'user_id'               => $user->id,
            'user_satisfaction_id'  => $userSatisfaction->id,
            'review_sent'           => 0
        ]);

        $genericSurveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id'     => $survey->id,
            'question_id'   => $genericQuestion->question_id,
            'active'        => 1,
            'required'      => 1
        ]);

        factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $question->id,
            'required' => 1,
            'active' => 1,
            'type' => "Multiresponse",
            'allow_multiple_responses' => 1
        ]);
        $surveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id'     => $survey->id,
            'question_id'   => $question->id,
            'active'        => 1,
            'required'      => 1
        ]);
        factory(QuestionText::class)->create([
            'question_id' => $question->id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $firstQuestionResponse = factory(QuestionResponse::class)->create([
            'question_id' => $question->id
        ]);

        $secondQuestionResponse = factory(QuestionResponse::class)->create([
            'question_id' => $question->id
        ]);

        factory(QuestionResponseText::class)->create([
            'question_response_id' => $firstQuestionResponse->id,
            'lang_value' => 'en'
        ]);

        factory(QuestionResponseText::class)->create([
            'question_response_id' => $secondQuestionResponse->id,
            'lang_value' => 'en'
        ]);


        $genericResponse = factory(UserSurveyQuestionAnswer::class)->create([
            'survey_question_id'    => $genericSurveyQuestion->id,
            'user_survey_id'        => $userSurvey->id,
        ]);

        $request = new \Illuminate\Http\Request();
        $request->replace([
            'brand_id'      => $brand->id,
            'survey_id'     => $userSatisfaction->id,
            'question_id'   => $question->id,
            'user_id'       => $user->id,
            'response_id'   => [$firstQuestionResponse->id, $secondQuestionResponse->id],
            'score'         => null,
            'comment'       => ""
        ]);

        Event::fake();
        $data = new SaveSurveyAnswersDataType($request);
        $job = new ProcessSaveSurveyAnswer($data);
        $job->handle(
            app('App\Services\BrandService'),
            app('App\Services\Users\UserService'),
            app('App\Services\Connections\ConnectionHistoryService'),
            app('App\Repositories\Satisfactions\CustomizedSatisfactionAnswerRepository'),
            app('App\Services\Products\SatisfactionSurveyProductService'),
            app('App\Services\Users\UserBrandService'),
            app('App\Services\Visits\VisitService')
        );

        Event::assertDispatched(SurveyQuestionAnsweredEvent::class, function ($event) use ($genericResponse) {
            $this->assertStringContainsString("Question text test", json_encode(data_get($event, 'payload.survey.summary')));
            $this->assertStringNotContainsString("Disabled Question text test", json_encode(data_get($event, 'payload.survey.summary')));

            return
                data_get($event, 'payload.survey.type') === "customized" &&
                data_get($event, 'payload.survey.summary') &&
                data_get($event, 'payload.survey.score') == null &&
                data_get($event, 'payload.survey.comment') == "" &&
                data_get($event, 'payload.survey.generic.score') == $genericResponse->answer &&
                data_get($event, 'payload.survey.generic.comment') == $genericResponse->comment;
        });

        $firstUserSurveyQuestionAnswer = UserSurveyQuestionAnswer::where([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $surveyQuestion->id,
            'question_response_id' => $firstQuestionResponse->id
        ])->first();

        $secondUserSurveyQuestionAnswer = UserSurveyQuestionAnswer::where([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $surveyQuestion->id,
            'question_response_id' => $secondQuestionResponse->id
        ])->first();

        // Test data saved on new system tables
        $this->assertEquals($firstUserSurveyQuestionAnswer->answer, null);
        $this->assertEquals($firstUserSurveyQuestionAnswer->comment, null);
        $this->assertEquals($secondUserSurveyQuestionAnswer->answer, null);
        $this->assertEquals($secondUserSurveyQuestionAnswer->comment, null);

        // Test data saved on old system tables
        $firstCustomizedSatisfaction = SatisfactionAnswer::where([
            'survey_question_id'    => $question->id,
            'user_satisfaction_id'  => $userSatisfaction->id,
            'question_response_id' => $firstQuestionResponse->id
        ])->first();
        $secondCustomizedSatisfaction = SatisfactionAnswer::where([
            'survey_question_id'    => $question->id,
            'user_satisfaction_id'  => $userSatisfaction->id,
            'question_response_id' => $secondQuestionResponse->id
        ])->first();

        $this->assertEquals($firstCustomizedSatisfaction->answer, null);
        $this->assertEquals($firstCustomizedSatisfaction->comment, null);
        $this->assertEquals($secondCustomizedSatisfaction->answer, null);
        $this->assertEquals($secondCustomizedSatisfaction->comment, null);
    }
}
