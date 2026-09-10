<?php

namespace Tests\Feature;

use App\Brand;
use App\BrandUrlLanguage;
use App\Hotel;
use App\HotelSatisfaction;
use App\Jobs\ProcessSnsEvent;
use App\Language;
use App\BrandProduct;
use App\Services\Connections\ConnectionTest;
use App\QuestionBrand;
use App\User;
use App\Satisfaction;
use App\Survey;
use App\SurveyQuestion;
use App\UserSurveyQuestionAnswer;
use App\UserSurvey;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Events\Brand\BrandUpdated;

class WorkerSurveyEventsTest extends TestCase
{
    private $productService;
    private $brandProductService;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake(BrandUpdated::class);

        $this->productService = $this->app->make('App\Services\Products\ProductService');
        $this->brandProductService = $this->app->make('App\Services\BrandProductService');
    }

    public function testSatisfactionSurveyAnswered()
    {
        //first we create a custom configuration
        $hotel = factory(Hotel::class)->create();

        $brand = $hotel->brand;
        $product = $this->productService->getByName('customized_satisfaction_surveys');

        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id'   => $brand->id,
            'product_id' => $product->id,
            'active'     => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);
        $language = Language::inRandomOrder()
            ->first();

        $brandUrlLanguage = factory(BrandUrlLanguage::class)->create([
            'brand_id'    => $brand->id,
            'language_id' => $language->id
        ]);

        $user = factory(User::class)->create();

        $satisfaction = factory(Satisfaction::class)->create([
            'id_hotel'   => $hotel->id,
            'id_usuario' => $user->id,
            'done'       => 0,
        ]);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id
        ]);

        $userSurvey = factory(UserSurvey::class)->create([
            'brand_id'             => $brand->id,
            'survey_id'            => $survey->id,
            'user_id'              => $user->id,
            'assisted_staff_id'    => null,
            'user_satisfaction_id' => $satisfaction->id
        ]);

        $defaultQuestion = QuestionBrand::whereNull('brand_id')->first();
        $surveyQuestion = factory(SurveyQuestion::class)->create([
            "survey_id"   => $survey->id,
            "question_id" => $defaultQuestion->question_id
        ]);

        $language = Language::where('name', '=', $user->lang)->first();
        $brandUrlLanguage = BrandUrlLanguage::where([
            'brand_id' => $brand->id,
            'language_id' => $language->id
        ])->first();

        $connection = new ConnectionTest();
        $c = new  ProcessSnsEvent(
            '{"context":{"brandID": ' . $brand->id . '},"eventSource": "https://hotelinking.com","origin": "survey_vue/{survey_id}","originalEntity": "survey_vue","payload": {"user": {"id" : ' . $user->id . '},"brand": {"id": ' . $brand->id . '},"satisfaction": { "id": ' . $satisfaction->id . ', "comment":"test!", "score":"1"}},"schema": "com.hotelinking/Surveys/satisfaction_survey_answered/2.0.0"}',
            $connection
        );

        $c->handle();


        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(in_array("com.hotelinking/Emails/send_satisfaction_thanks_email/1.0.0", $eventsEmitted), 1, $connection->getValidationErrors());
        $answer = Satisfaction::find($satisfaction->id);
        $surveyQuestionAnswer = UserSurveyQuestionAnswer::where('survey_question_id', $surveyQuestion->id)->first();

        $this->assertEquals($answer->comment, "test!");
        $this->assertEquals($surveyQuestionAnswer->comment, "test!");
    }

    public function testSatisfactionSurveyAnsweredAndWarningSendRegardlessScore()
    {
        //first we create a custom configuration
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $product = $this->productService->getByName('customized_satisfaction_surveys');

        $oldBrandProduct = factory(BrandProduct::class)->create([
            'brand_id'   => $brand->id,
            'product_id' => $product->id,
            'active'     => 0
        ]);

        if ($product) {
            $this->json('PUT', "/api/brands/$brand->id/products/$product->id/activate/1")
                ->assertStatus(204);
        }

        $newBrandProduct = $this->brandProductService->get($brand->id, $product->id);

        $this->assertNotEquals($oldBrandProduct->active, $newBrandProduct->active);

        $hotelSatisfaction = HotelSatisfaction::where('id_hotel', $hotel->id)->first();
        $hotelSatisfaction->warning_email = 'test@test.com';
        $hotelSatisfaction->filter_warning = 0;
        $hotelSatisfaction->save();

        $user = factory(User::class)->create();

        $satisfaction = factory(Satisfaction::class)->create([
            'id_hotel'   => $hotel->id,
            'id_usuario' => $user->id,
            'done'       => 0,
        ]);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id
        ]);

        $userSurvey = factory(UserSurvey::class)->create([
            'brand_id'             => $brand->id,
            'survey_id'            => $survey->id,
            'user_id'              => $user->id,
            'assisted_staff_id'    => null,
            'user_satisfaction_id' => $satisfaction->id
        ]);

        $defaultQuestion = QuestionBrand::whereNull('brand_id')->first();
        factory(SurveyQuestion::class)->create([
            "survey_id"   => $survey->id,
            "question_id" => $defaultQuestion->question_id
        ]);

        $language = Language::where('name', '=', $user->lang)->first();

        $brandUrlLanguage = factory(BrandUrlLanguage::class)->create([
            'brand_id'    => $brand->id,
            'language_id' => $language->id
        ]);

        $connection = new ConnectionTest();
        $c = new  ProcessSnsEvent(
            '{"context":{"brandID": ' . $brand->id . '},"eventSource": "https://hotelinking.com","origin": "survey_vue/{survey_id}","originalEntity": "survey_vue","payload": {"user": {"id" : ' . $user->id . '},"brand": {"id": ' . $brand->id . '},"satisfaction": { "id" : ' . $satisfaction->id . ', "comment":"test!", "score":"10"}},"schema": "com.hotelinking/Surveys/satisfaction_survey_answered/2.0.0"}',
            $connection
        );

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(in_array("com.hotelinking/Emails/send_satisfaction_warning_email/2.0.0", $eventsEmitted), 1, $connection->getValidationErrors());
        $this->assertEquals(in_array("com.hotelinking/Emails/send_satisfaction_thanks_email/1.0.0", $eventsEmitted), 1, $connection->getValidationErrors());
        $answer = Satisfaction::find($satisfaction->id);

        $this->assertEquals($answer->comment, "test!");
    }
}
