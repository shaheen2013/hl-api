<?php

namespace Tests\Feature;

use App\Brand;
use App\Hotel;
use App\HotelSatisfaction;
use App\Cadena;
use App\CadenaHotel;
use App\User;
use App\Satisfaction;
use App\SatisfactionAnswer;
use App\Category;
use App\CategoryText;
use App\Question;
use App\QuestionBrand;
use App\QuestionText;
use Tests\TestCase;
use Illuminate\Support\Arr;

class SurveyCategoryControllerTest extends TestCase
{
    public function testSurveyCategoryAreShowCorrectly()
    {
        $surveyCategory = $this->prepareSurveyCategory();
        $surveyCategoryText = factory(CategoryText::class)->create([
            'category_id' => $surveyCategory->id,
            'lang_value' => 'en',
            'text' => "Category text test"
        ]);

        $this->json('GET', "/api/brands/" . $surveyCategory->brand_id . "/surveys/satisfactions/categories/?lang=en")
            ->assertStatus(200)
            ->assertJson([[
                'brand_id' => $surveyCategory->brand_id,
                'survey_category_text' => [["text" => $surveyCategoryText->text]]
            ]]);
    }

    public function testSurveyCategoryAreCreatedCorrectly()
    {

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        $surveyCategoryCreated = $this->json(
            'POST',
            "/api/brands/" . $brand->id . "/surveys/satisfactions/categories",
            [
                'survey_category_text' => json_encode([["lang_value" => "en","text" => "Category one"],["lang_value" => "es","text" => "Categoría uno"]])
            ],
            ['contentType' => 'application/json']
        )
            ->assertStatus(200)
            ->assertJson(["brand_id" => $brand->id]);

        // Assert that insert question on correct category
        $surveyCategory = Category::where('id', $surveyCategoryCreated->baseResponse->original->id)->first();
        $this->assertEquals($surveyCategory->id, $surveyCategoryCreated->baseResponse->original->id);

        // Assert that insert all the languages correctly
        $surveyCategoryText = $surveyCategory->load('categoryText')->categoryText;
        $englishText = $this->getCategoryText($surveyCategoryText, 'en');
        $spanishText = $this->getCategoryText($surveyCategoryText, 'es');

        $this->assertEquals($englishText, "Category one");
        $this->assertEquals($spanishText, "Categoría uno");
    }

    public function testSurveyCategoryAreUpdatedCorrectly()
    {

        $surveyCategory = $this->prepareSurveyCategory();

        $englishSurveyCategoryText = factory(CategoryText::class)->create([
            'category_id'   => $surveyCategory->id,
            'lang_value'    => 'en',
            'text'          => "Question text test"
        ]);

        $surveyCategoryCreated = $this->json(
            'PUT',
            "/api/brands/" . $surveyCategory->brand_id . "/surveys/satisfactions/categories/" . $surveyCategory->id,
            [
                'survey_category_text' => json_encode([
                    ["lang_value" => "en","text" => "updated en"],
                    ["lang_value" => "es","text" => "updated es"],
                    ["lang_value" => "ca","text" => "updated ca"],
                    ["lang_value" => "de","text" => "updated de"],
                    ["lang_value" => "fr","text" => "updated fr"],
                    ["lang_value" => "it","text" => "updated it"],
                    ["lang_value" => "zh","text" => "updated zh"],
                ])
            ],
            ['contentType' => 'application/json']
        )->assertStatus(204);

        // Assert that insert question on correct category
        $this->assertEquals($englishSurveyCategoryText->fresh()->text, "updated en");

        $updatedEs = CategoryText::where([
            "category_id"   => $surveyCategory->id,
            "lang_value"    => "es"
        ])->first();

        $this->assertEquals($updatedEs->text, "updated es");
    }

    public function testUpdateCategoryFromAnotherBrandFails()
    {
        $hotel = factory(Hotel::class)->create();
        $anotherBrand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $surveyCategory = $this->prepareSurveyCategory();


        $surveyCategoryCreated = $this->json(
            'PUT',
            "/api/brands/" . $anotherBrand . "/surveys/satisfactions/categories/" . $surveyCategory->id,
            [
                'survey_category_text' => json_encode([
                    ["lang_value" => "en","text" => "updated en"],
                    ["lang_value" => "es","text" => "updated es"],
                    ["lang_value" => "ca","text" => "updated ca"],
                    ["lang_value" => "de","text" => "updated de"],
                    ["lang_value" => "fr","text" => "updated fr"],
                    ["lang_value" => "it","text" => "updated it"],
                    ["lang_value" => "zh","text" => "updated zh"],
                ])
            ],
            ['contentType' => 'application/json']
        )->assertStatus(404);
    }

    public function testUpdateCategoryWithoutAllLanguagesFails()
    {
        $surveyCategory = $this->prepareSurveyCategory();

        $surveyCategoryCreated = $this->json(
            'PUT',
            "/api/brands/" . $surveyCategory->brand_id . "/surveys/satisfactions/categories/" . $surveyCategory->id,
            [
                'survey_category_text' => json_encode([
                    ["lang_value" => "en","text" => "updated en"],
                ])
            ],
            ['contentType' => 'application/json']
        )->assertStatus(400);
    }

    public function testSurveyCategoryAreDeletedCorrectly()
    {
        $surveyCategory = $this->prepareSurveyCategory();

        $surveyCategoryText = factory(CategoryText::class)->create([
            'category_id' => $surveyCategory->id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $surveyQuestion = factory(Question::class)->create([
            'category_id' => $surveyCategory->id,
        ]);

        $surveyQuestionBrand = factory(QuestionBrand::class)->create([
            'brand_id' => $surveyCategory->brand_id,
            'question_id' => $surveyQuestion->id,
            'required' => 0
        ]);

        $surveyQuestionText = factory(QuestionText::class)->create([
            'question_id' => $surveyQuestion->id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $user = factory(User::class)->create(['lang' => 'en']);

        $satisfaction = factory(Satisfaction::class)->create([
            'id_hotel'      => $surveyQuestionBrand->load('brand')->brand->hotel_id,
            'id_usuario'    => $user->id,
            'done'          => 1
        ]);

        $satisfactionAnswer = factory(SatisfactionAnswer::class)->create([
            'user_satisfaction_id' => $satisfaction->id,
            'survey_question_id' => $surveyQuestionBrand->question_id,
            'brand_id' => $surveyCategory->brand_id
        ]);

        $this->json('DELETE', "/api/brands/" . $surveyQuestionBrand->brand_id . "/surveys/satisfactions/categories/" . $surveyCategory->id . "/")
            ->assertStatus(204);

        // Assert deleting in cascade
        $this->assertEquals($surveyCategory->fresh(), null);
        $this->assertEquals($surveyCategoryText->fresh(), null);
        $this->assertEquals($surveyQuestion->fresh(), null);
        $this->assertEquals($surveyQuestionBrand->fresh(), null);
        $this->assertEquals($surveyQuestionText->fresh(), null);
        $this->assertEquals($satisfactionAnswer->fresh(), null);
    }

    public function testSurveyCategoryAreDeletedInChainCorrectly()
    {
        $hotel = factory(Hotel::class)->create();
        $chain = factory(Cadena::class)->create();
        factory(CadenaHotel::class)->create(
            [
                'id_cadena' => $chain->id,
                'id_hotel'  => $hotel->id
            ]
        );

        $brand = Brand::where('chain_id', '=', $chain->id)->first();
        $childBrand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $surveyCategory = factory(Category::class)->create([
            'brand_id' => $brand->id
        ]);

        $surveyCategoryText = factory(CategoryText::class)->create([
            'category_id' => $surveyCategory->id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $surveyQuestion = factory(Question::class)->create([
            'category_id' => $surveyCategory->id,
        ]);

        $surveyQuestionBrand = factory(QuestionBrand::class)->create([
            'brand_id' => $surveyCategory->brand_id,
            'question_id' => $surveyQuestion->id,
            'required' => 0
        ]);

        $surveyQuestionText = factory(QuestionText::class)->create([
            'question_id' => $surveyQuestion->id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $user = factory(User::class)->create(['lang' => 'en']);

        factory(HotelSatisfaction::class)->create([
            'id_hotel' => $hotel->id,
            'childBrandQuestionsID' => $childBrand->id
        ]);

        $satisfaction = factory(Satisfaction::class)->create([
            'id_hotel'      => $hotel->id,
            'id_usuario'    => $user->id,
            'done'          => 1
        ]);

        $satisfactionAnswer = factory(SatisfactionAnswer::class)->create([
            'user_satisfaction_id' => $satisfaction->id,
            'survey_question_id' => $surveyQuestionBrand->question_id,
            'brand_id' => $surveyCategory->brand_id
        ]);

        $this->json('DELETE', "/api/brands/" . $surveyQuestionBrand->brand_id . "/surveys/satisfactions/categories/" . $surveyCategory->id . "/")
            ->assertStatus(204);

        // Assert deleting in cascade
        $this->assertEquals($surveyCategory->fresh(), null);
        $this->assertEquals($surveyCategoryText->fresh(), null);
        $this->assertEquals($surveyQuestion->fresh(), null);
        $this->assertEquals($surveyQuestionBrand->fresh(), null);
        $this->assertEquals($surveyQuestionText->fresh(), null);
        $this->assertEquals($satisfactionAnswer->fresh(), null);
    }

    private function prepareSurveyCategory()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $surveyCategory = factory(Category::class)->create([
            'brand_id' => $brand->id
        ]);

        return $surveyCategory;
    }

    private function getCategoryText($questions, $lang)
    {

        $questionTranslation = Arr::first($questions, function ($question) use ($lang) {
            return $question['lang_value'] == $lang;
        });

        return data_get($questionTranslation, 'text');
    }
}
