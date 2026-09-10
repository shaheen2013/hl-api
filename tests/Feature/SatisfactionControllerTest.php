<?php

namespace Tests\Feature;

use App\Brand;
use App\BrandProduct;
use App\Category;
use App\CategoryText;
use App\Hotel;
use App\QuestionBrand;
use App\HotelStaff;
use App\HotelStaffHotels;
use App\Satisfaction;
use App\SatisfactionAnswer;
use App\Question;
use App\QuestionText;
use App\QuestionResponse;
use App\QuestionResponseText;
use App\Survey;
use App\SurveyQuestion;
use App\User;
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use App\Jobs\ProcessSurveyExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Event;
use Carbon\Carbon;
use Tests\TestCase;

class SatisfactionControllerTest extends TestCase
{
    private $productService;
    private $brandProductService;
    private $satisfactionSurveyProductService;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->productService = $this->app->make('App\Services\Products\ProductService');
        $this->brandProductService = $this->app->make('App\Services\BrandProductService');
        $this->satisfactionSurveyProductService = $this->app->make('App\Services\Products\SatisfactionSurveyProductService');
    }

    public function testSatisfactionsAreShowCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        $user = factory(User::class)->create(['lang' => 'en']);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id,
            'name' => 'survey_name'
        ]);

        $category = factory(Category::class)->create(['brand_id' => $brand->id]);

        $question = factory(Question::class)->create(['category_id' => $category->id]);

        $userSatisfaction = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'puntuacion'    => 10,
            'done'          => 1
        ]);

        $userSurvey = factory(UserSurvey::class)->create([
            'survey_id' => $survey->id,
            'brand_id' => $brand->id,
            'user_id' => $user->id,
            'user_satisfaction_id' => $userSatisfaction->id
        ]);

        $surveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $question->id,
            'active' => 1,
            'required' => 1
        ]);

        $satisfaction = factory(UserSurveyQuestionAnswer::class)->create([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $surveyQuestion->id,
            'answer' => 10,
        ]);

        $this->json('GET', "/api/satisfactions/" . $brand->id . "/en/8/" . PHP_INT_MIN)
        ->assertStatus(200)
        ->assertJson([[
            'comment' => $satisfaction->comment]
        ]);

        $this->json('GET', "/api/satisfactions/" . $brand->id . "/ru/8/" . PHP_INT_MIN)
        ->assertStatus(200)
        ->assertJson([[
            'comment' => $satisfaction->comment]
        ]);
    }

    public function testGetSurveysWithAllQuestionTypesShowCorrectly()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $user = factory(User::class)->create(['lang' => 'en']);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id,
            'name' => 'survey_name'
        ]);

        $userSatisfaction = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'done'          => 1
        ]);
        $userSurvey = factory(UserSurvey::class)->create([
            'survey_id' => $survey->id,
            'brand_id' => $brand->id,
            'user_id' => $user->id,
            'user_satisfaction_id' => $userSatisfaction->id
        ]);

        // Generic Question
        $genericQuestion = QuestionBrand::whereNull('brand_id')->first();
        $genericSurveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id'     => $survey->id,
            'question_id'   => $genericQuestion->question_id,
            'active'        => 1,
            'required'      => 1
        ]);
        factory(UserSurveyQuestionAnswer::class)->create([
            'survey_question_id'    => $genericSurveyQuestion->id,
            'user_survey_id'        => $userSurvey->id,
            'answer'                => $userSatisfaction->puntuacion,
            'comment'               => $userSatisfaction->comentario,
        ]);

        $category = factory(Category::class)->create(['brand_id' => $brand->id]);
        $categoryEn = factory(CategoryText::class)->create([
            'category_id' => $category->id,
            'lang_value' => 'en'
        ]);
        $categoryEs = factory(CategoryText::class)->create([
            'category_id' => $category->id,
            'lang_value' => 'es'
        ]);
        
       
        // Rating Question
        $ratingQuestion = factory(Question::class)->create(['category_id' => $category->id]);
        $ratingQuestionEn = factory(QuestionText::class)->create([
            'question_id' => $ratingQuestion->id,
            'lang_value' => 'en'
        ]);
        $ratingQuestionEs = factory(QuestionText::class)->create([
            'question_id' => $ratingQuestion->id,
            'lang_value' => 'es'
        ]);
        factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $ratingQuestion->id,
            'required' => 1,
            'active' => 1,
            'type' => "Rating"
        ]);
        $ratingSurveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $ratingQuestion->id,
            'active' => 1,
            'required' => 1
        ]);

        $customizedSatisfactionAnswerRating = factory(SatisfactionAnswer::class)->create([
            'user_satisfaction_id' => $userSatisfaction->id,
            'survey_question_id' => $ratingQuestion->id,
            'brand_id' => $brand->id,
        ]);

        factory(UserSurveyQuestionAnswer::class)->create([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $ratingSurveyQuestion->id,
            'answer' => $customizedSatisfactionAnswerRating->answer,
            'comment' => $customizedSatisfactionAnswerRating->comment
        ]);

        // Multiresponse Question with one answer
        $singleMultiresponseQuestion = factory(Question::class)->create(['category_id' => $category->id]);
        $singleMultiresponseQuestionEn = factory(QuestionText::class)->create([
            'question_id' => $singleMultiresponseQuestion->id,
            'lang_value' => 'en'
        ]);
        $singleMultiresponseQuestionEs = factory(QuestionText::class)->create([
            'question_id' => $singleMultiresponseQuestion->id,
            'lang_value' => 'es'
        ]);
        $questionResponse = factory(QuestionResponse::class)->create([
            'question_id' => $singleMultiresponseQuestion->id
        ]);
        $singleMultiresponseQuestionResponseTextEn = factory(QuestionResponseText::class)->create([
            'question_response_id' => $questionResponse->id,
            'lang_value' => 'en'
        ]);

        $singleMultiresponseQuestionResponseTextEs = factory(QuestionResponseText::class)->create([
            'question_response_id' => $questionResponse->id,
            'lang_value' => 'es'
        ]);
        factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $singleMultiresponseQuestion->id,
            'required' => 1,
            'active' => 1,
            'type' => "Multiresponse",
            'allow_multiple_responses' => 0
        ]);
        $singleMultiresponseSurveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $singleMultiresponseQuestion->id,
            'active' => 1,
            'required' => 1
        ]);

        $customizedSatisfactionAnswerSingleMultiresponse = factory(SatisfactionAnswer::class)->create([
            'user_satisfaction_id' => $userSatisfaction->id,
            'survey_question_id' => $singleMultiresponseQuestion->id,
            'brand_id' => $brand->id,
            'answer' => null,
            'comment' => null,
            'question_response_id' => $questionResponse->id
        ]);

        factory(UserSurveyQuestionAnswer::class)->create([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $singleMultiresponseSurveyQuestion->id,
            'answer' => $customizedSatisfactionAnswerSingleMultiresponse->answer,
            'comment' => $customizedSatisfactionAnswerSingleMultiresponse->comment,
            'question_response_id' => $questionResponse->id,
        ]);

        // Multiresponse Question with multiple answers
        $multipleMultiresponseQuestion = factory(Question::class)->create(['category_id' => $category->id]);
        $multipleMultiresponseQuestionEn = factory(QuestionText::class)->create([
            'question_id' => $multipleMultiresponseQuestion->id,
            'lang_value' => 'en'
        ]);
        $multipleMultiresponseQuestionEs = factory(QuestionText::class)->create([
            'question_id' => $multipleMultiresponseQuestion->id,
            'lang_value' => 'es'
        ]);
        $questionResponse = factory(QuestionResponse::class)->create([
            'question_id' => $multipleMultiresponseQuestion->id
        ]);
        $questionResponse2 = factory(QuestionResponse::class)->create([
            'question_id' => $multipleMultiresponseQuestion->id
        ]);

        $multipleMultiresponseQuestionResponseTextEn = factory(QuestionResponseText::class)->create([
            'question_response_id' => $questionResponse->id,
            'lang_value' => 'en'
        ]);

        $multipleMultiresponseQuestionResponseTextEn2 = factory(QuestionResponseText::class)->create([
            'question_response_id' => $questionResponse2->id,
            'lang_value' => 'en'
        ]);

        $multipleMultiresponseQuestionResponseTextEs = factory(QuestionResponseText::class)->create([
            'question_response_id' => $questionResponse->id,
            'lang_value' => 'es'
        ]);
       
        $multipleMultiresponseQuestionResponseTextEs2 = factory(QuestionResponseText::class)->create([
            'question_response_id' => $questionResponse2->id,
            'lang_value' => 'es'
        ]);
        
        factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $multipleMultiresponseQuestion->id,
            'required' => 1,
            'active' => 1,
            'type' => "Multiresponse",
            'allow_multiple_responses' => 1
        ]);
        $multipleMultiresponseSurveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $multipleMultiresponseQuestion->id,
            'active' => 1,
            'required' => 1
        ]);

        $customizedSatisfactionAnswerMultipleMultiresponse = factory(SatisfactionAnswer::class)->create([
            'user_satisfaction_id' => $userSatisfaction->id,
            'survey_question_id' => $multipleMultiresponseQuestion->id,
            'brand_id' => $brand->id,
            'answer' => null,
            'comment' => null,
            'question_response_id' => $questionResponse->id
        ]);

        factory(SatisfactionAnswer::class)->create([
            'user_satisfaction_id' => $userSatisfaction->id,
            'survey_question_id' => $multipleMultiresponseQuestion->id,
            'brand_id' => $brand->id,
            'answer' => null,
            'comment' => null,
            'question_response_id' => $questionResponse2->id
        ]);

        factory(UserSurveyQuestionAnswer::class)->create([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $multipleMultiresponseSurveyQuestion->id,
            'answer' => $customizedSatisfactionAnswerMultipleMultiresponse->answer,
            'comment' => $customizedSatisfactionAnswerMultipleMultiresponse->comment,
            'question_response_id' => $questionResponse->id,
        ]);

        factory(UserSurveyQuestionAnswer::class)->create([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $multipleMultiresponseSurveyQuestion->id,
            'answer' => $customizedSatisfactionAnswerMultipleMultiresponse->answer,
            'comment' => $customizedSatisfactionAnswerMultipleMultiresponse->comment,
            'question_response_id' => $questionResponse2->id,
        ]);

        // Open question
        $openQuestion = factory(Question::class)->create(['category_id' => $category->id]);
        $openQuestionEn = factory(QuestionText::class)->create([
            'question_id' => $openQuestion->id,
            'lang_value' => 'en'
        ]);
        $openQuestionEs = factory(QuestionText::class)->create([
            'question_id' => $openQuestion->id,
            'lang_value' => 'es'
        ]);

        factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $openQuestion->id,
            'required' => 1,
            'active' => 1,
            'type' => "Multiresponse",
            'allow_multiple_responses' => 0
        ]);
        $openSurveyQuestion = factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $openQuestion->id,
            'active' => 1,
            'required' => 1
        ]);

        $customizedSatisfactionAnswerOpen = factory(SatisfactionAnswer::class)->create([
            'user_satisfaction_id' => $userSatisfaction->id,
            'survey_question_id' => $openQuestion->id,
            'brand_id' => $brand->id,
            'answer' => null,
            'comment' => "Open question comment"
        ]);

        factory(UserSurveyQuestionAnswer::class)->create([
            'user_survey_id' => $userSurvey->id,
            'survey_question_id' => $openSurveyQuestion->id,
            'answer' => $customizedSatisfactionAnswerOpen->answer,
            'comment' => $customizedSatisfactionAnswerOpen->comment
        ]);

        $this->json('GET', "/api/brands/" . $brand->id . "/surveys/satisfactions")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $userSatisfaction->id,
                        'userSurveyId' => $userSurvey->id,
                        'comment' => $userSatisfaction->comentario,
                        'score' => $userSatisfaction->puntuacion,
                        'sendDate' => Carbon::createFromFormat('Y-m-d H:i:s', $userSatisfaction->send_date->format('Y-m-d H:i:s'), 'UTC')->setTimezone($hotel->timeZone->time_zone)->toDateTimeString(),
                        'answered' => Carbon::createFromFormat('Y-m-d H:i:s', $userSatisfaction->fecha_update->format('Y-m-d H:i:s'), 'UTC')->setTimezone($hotel->timeZone->time_zone)->toDateTimeString(),
                        'customSendDate' => $userSatisfaction->customized_send_date->format('Y-m-d H:i:s'),
                        'hasBeenSeen' => $userSatisfaction->has_been_seen,
                        'whoHasBeenSeen' => $userSatisfaction->who_has_been_seen,
                        'incidents_reviewed' => $userSurvey->incidents_reviewed,
                        'roomID' => $userSatisfaction->id_room,
                        'done'  => 1,
                        'favorite' => $userSatisfaction->favorite,
                        'reviewSend' => $userSatisfaction->review_send,
                        'user' => [
                            'id' => $user->id,
                            'email' => $user->email,
                            'name' => $user->name,
                            'lang' => $user->lang,
                            'location' => $user->location,
                            'brithday' => $user->birthday,
                            'gender' => $user->gender,
                            'country' => $user->country,
                        ],
                        'hotel' => [
                            'id' => $hotel->id,
                            'name' => $hotel->name,
                            'brandID' => $brand->id,
                            'logo' => $hotel->logo,
                        ],
                        'incidents' => [],
                        "satisfactionAnswers" => [
                            [
                                "id" =>  $customizedSatisfactionAnswerRating->survey_question_id,
                                "answer" => $customizedSatisfactionAnswerRating->answer,
                                "comment" => $customizedSatisfactionAnswerRating->comment,
                                "surveyCategory" => [
                                    "en" => $categoryEn->text,
                                    "es" => $categoryEs->text
                                ],
                                "questions" =>  [
                                    "en" => $ratingQuestionEn->text,
                                    "es" => $ratingQuestionEs->text
                                ]
                            ],
                            [
                                "id" =>  $customizedSatisfactionAnswerSingleMultiresponse->survey_question_id,
                                "answer" => [
                                    "en" => $singleMultiresponseQuestionResponseTextEn->text,
                                    "es" => $singleMultiresponseQuestionResponseTextEs->text,
                                ],
                                "comment" => $customizedSatisfactionAnswerSingleMultiresponse->comment,
                                "surveyCategory" => [
                                    "en" => $categoryEn->text,
                                    "es" => $categoryEs->text
                                ],
                                "questions" =>  [
                                    "en" => $singleMultiresponseQuestionEn->text,
                                    "es" => $singleMultiresponseQuestionEs->text
                                ]
                            ],
                            [
                                "id" =>  $customizedSatisfactionAnswerMultipleMultiresponse->survey_question_id,
                                "answer" => [
                                    "en" => $multipleMultiresponseQuestionResponseTextEn->text . ', ' . $multipleMultiresponseQuestionResponseTextEn2->text,
                                    "es" => $multipleMultiresponseQuestionResponseTextEs->text . ', ' . $multipleMultiresponseQuestionResponseTextEs2->text,
                                ],
                                "comment" => $customizedSatisfactionAnswerMultipleMultiresponse->comment,
                                "surveyCategory" => [
                                    "en" => $categoryEn->text,
                                    "es" => $categoryEs->text
                                ],
                                "questions" =>  [
                                    "en" => $multipleMultiresponseQuestionEn->text,
                                    "es" => $multipleMultiresponseQuestionEs->text
                                ]
                            ],
                            [
                                "id" =>  $customizedSatisfactionAnswerOpen->survey_question_id,
                                "answer" => $customizedSatisfactionAnswerOpen->answer,
                                "comment" => $customizedSatisfactionAnswerOpen->comment,
                                "surveyCategory" => [
                                    "en" => $categoryEn->text,
                                    "es" => $categoryEs->text
                                ],
                                "questions" =>  [
                                    "en" => $openQuestionEn->text,
                                    "es" => $openQuestionEs->text
                                ]
                            ],
                        ]
                    ]
                ]
            ]);
    }

    public function testCustomizedSatisfactionBrandPermissionsAreSetCorrectly()
    {

        //first we create a custom configuration

        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('customized_satisfaction_surveys');

        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id' => $brand->id,
            'product_id' => $product->id,
            'active' => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);

        //then we recover it
        $answer = null;
        if ($product) {
            $this->json('GET', "/api/brands/$brand->id/products/$product->id/configuration")
                ->assertStatus(200)
                ->assertJson([
                    'customizedSendDays' => 0,
                    'customizedSendHours' => 0,
                    'customizedChainActivated' => 0,
                    'brandID' =>  $brand->id
                ]);
        }

        $payload = [
            'customizedActive' => 1,
            'customizedType' => 'Joined with satisfaction' ,
            'customizedSendDays' => '10',
            'customizedSendHours' => '5',
            'customizedChainActivated' => '1'
        ];

        $this->json('PUT', "/api/brands/$brand->id/products/$product->id/configuration", $payload)
            ->assertStatus(204);

        $this->assertEquals($brand->fresh()->hotel->hotelSatisfaction->customized_type, 'Joined with satisfaction');

        $user = factory(User::class)->create(['lang' => 'en']);

        $satisfaction = factory(Satisfaction::class)->create([
            'id_hotel' => $hotel->id,
            'id_usuario' => $user->id,
            'puntuacion' => 10,
            'done' => 1
        ]);

        $payload = [
        ];
        $this->json('GET', "/api/brands/$brand->id/surveys/satisfactions/", $payload)
            ->assertStatus(200)
            ->assertJson([
                "data" => [['score' => 10]]
            ]);

        $this->json('GET', "/api/brands/$brand->id/surveys/satisfactions/$satisfaction->id", $payload)
            ->assertStatus(200)
            ->assertJson([
                "data" => [['score' => 10]]
            ]);
    }

    public function testSatisfactionsWithIncidentsAreSavedCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        $user = factory(User::class)->create(['lang' => 'en']);

        $userSatisfaction = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'puntuacion'    => 10,
            'done'          => 1
        ]);

        $hotelStaff = factory(HotelStaff::class)->create([
            'nombre'    => 'StaffTest',
            'id_role'   => 3,
            'activo'    => 1
        ]);

        $hotelStaffHotels = factory(HotelStaffHotels::class)->create([
            'hotel_id'          => $hotel->id,
            'hotel_staff_id'    => $hotelStaff->id
        ]);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id
        ]);

        $userSurvey = factory(UserSurvey::class)->create([
            'user_id'               => $user->id,
            'brand_id'              => $brand->id,
            'survey_id'             => $survey->id,
            'user_satisfaction_id'  => $userSatisfaction->id
        ]);


        $this->json('POST', "/api/satisfactions/$userSurvey->id/incidents/", [
            "hotel_staff_id" => $hotelStaff->id,
            "incident_text" => "Test Incident"
        ])->assertStatus(204);

        $request = new Request(["satisfaction_id" => $userSatisfaction->id]);

        // We check the assertion was Correct
        $satisfactions = $this->satisfactionSurveyProductService->getSurveys($brand->id, $request);

        foreach ($satisfactions as $satisfaction) {
            if ($satisfaction->indicents != null) {
                $this->assertEquals('Test Incident', $satisfaction->incidents[0]->indicent_text);
                $this->assertEquals($hotelStaff->id, $satisfaction->incidents[0]->hotel_staff_id);
            }
        }
    }
    
    public function testQueueReportSucessfully()
    {
        Queue::fake();
        $this->json('POST', "/api/brands/1/surveys/report", ["emails" => ["test@hotelinking.com"]])->assertStatus(202);
        Queue::assertPushed(ProcessSurveyExport::class);
    }
}
