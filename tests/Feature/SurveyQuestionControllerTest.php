<?php

namespace Tests\Feature;

use App\Brand;
use App\Cadena;
use App\Hotel;
use App\HotelSatisfaction;
use App\CadenaHotel;
use App\User;
use App\Satisfaction;
use App\SatisfactionAnswer;
use App\Category;
use App\Question;
use App\QuestionBrand;
use App\QuestionText;
use App\QuestionResponse;
use App\QuestionResponseText;
use App\Survey;
use App\SurveyQuestion;
use Tests\TestCase;
use Illuminate\Support\Arr;

class SurveyQuestionControllerTest extends TestCase
{
    public function testSurveyQuestionAreShowCorrectly()
    {

        $surveyQuestionBrand = $this->prepareSurveyQuestion();
        $surveyQuestionText = factory(QuestionText::class)->create([
            'question_id' => $surveyQuestionBrand->question_id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $this->json('GET', "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/")
            ->assertStatus(200)
            ->assertJson([[
                'questions' => [[
                    'active' => $surveyQuestionBrand->active,
                    'required' => $surveyQuestionBrand->required,
                    'type' => $surveyQuestionBrand->type,
                    'allow_multiple_responses' => $surveyQuestionBrand->allow_multiple_responses,
                    'question_text' => ["en" => $surveyQuestionText->text]
                ]]
            ]]);

        $this->json('GET', "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/?lang=en")
            ->assertStatus(200)
            ->assertJson([[
                'questions' => [[
                    'active' => $surveyQuestionBrand->active,
                    'required' => $surveyQuestionBrand->required,
                    'type' => $surveyQuestionBrand->type,
                    'allow_multiple_responses' => $surveyQuestionBrand->allow_multiple_responses,
                    'question_text' =>  $surveyQuestionText->text
                ]]
            ]]);

        // Getting language not existent on database will return english
        $this->json('GET', "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/?lang=ku")
            ->assertStatus(200)
            ->assertJson([[
                'questions' => [[
                    'active' => $surveyQuestionBrand->active,
                    'required' => $surveyQuestionBrand->required,
                    'type' => $surveyQuestionBrand->type,
                    'allow_multiple_responses' => $surveyQuestionBrand->allow_multiple_responses,
                    'question_text' =>  $surveyQuestionText->text
                ]]
            ]]);
    }

    public function testShowOnlyActiveSurveyCorrectly()
    {

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        factory(HotelSatisfaction::class)->create([
            'id_hotel' => $hotel->id,
            'customized_chain_activated' => 0
        ]);

        $surveyCategory = factory(Category::class)->create([
            'brand_id' => $brand->id
        ]);

        $surveyQuestion = factory(Question::class)->create([
            'category_id' => $surveyCategory->id,
        ]);

        $surveyQuestionDisabled = factory(Question::class)->create([
            'category_id' => $surveyCategory->id,
        ]);

        $surveyQuestionBrand = factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $surveyQuestion->id,
            'required' => 0,
            'active' => 1
        ]);

        $surveyQuestionBrandDisabled = factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $surveyQuestionDisabled->id,
            'required' => 0,
            'active' => 0
        ]);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id
        ]);

        factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $surveyQuestion->id,
            'required' => 0,
            'active' => 1
        ]);

        factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $surveyQuestionDisabled->id,
            'required' => 0,
            'active' => 0
        ]);

        $surveyQuestionText = factory(QuestionText::class)->create([
            'question_id' => $surveyQuestionBrand->question_id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $surveyQuestionDisabledText = factory(QuestionText::class)->create([
            'question_id' => $surveyQuestionBrandDisabled->question_id,
            'lang_value' => 'en',
            'text' => "Disabled Question"
        ]);


        $this->json('GET', "/api/brands/" . $brand->id . "/survey-questions/?active=1")
            ->assertStatus(200)
            ->assertJson([[
                'questions' => [['question_text' => ["en" => $surveyQuestionText->text]]]
            ]])
            ->assertJsonMissing([$surveyQuestionDisabledText->text]);


        $this->json('GET', "/api/brands/" . $brand->id . "/survey-questions/?lang=en&active=1")
            ->assertStatus(200)
            ->assertJson([[
                'questions' => [['question_text' =>  $surveyQuestionText->text]]
            ]])
            ->assertJsonMissing([$surveyQuestionDisabledText->text]);

        // Getting language not existent on database will return english
        $this->json('GET', "/api/brands/" . $brand->id . "/survey-questions/?lang=ku&active=1")
            ->assertStatus(200)
            ->assertJson([[
                'questions' => [['question_text' =>  $surveyQuestionText->text]]
            ]])
            ->assertJsonMissing([$surveyQuestionDisabledText->text]);
    }

    public function testSurveyMultiresponseQuestionAreShowCorrectly()
    {

        $surveyQuestionBrand = $this->prepareSurveyQuestion();
        $surveyQuestionText = factory(QuestionText::class)->create([
            'question_id' => $surveyQuestionBrand->question_id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $surveyResponse = factory(QuestionResponse::class)->create([
            'question_id' => $surveyQuestionBrand->question_id
        ]);

        $surveyResponseText1 = factory(QuestionResponseText::class)->create([
            'question_response_id' => $surveyResponse->id,
            'lang_value' => 'en'
        ]);

        $surveyResponseText2 = factory(QuestionResponseText::class)->create([
            'question_response_id' => $surveyResponse->id,
            'lang_value' => 'es'
        ]);

        $this->json('GET', "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/")
            ->assertStatus(200)
            ->assertJson([[
                'questions' => [
                    [
                        'question_text' => ["en" => $surveyQuestionText->text],
                        'question_responses' => [["en" => $surveyResponseText1->text, "es" => $surveyResponseText2->text]]
                    ],
                ]
            ]]);
    }

    public function testShowQuestionWithInvalidDataFails()
    {
        $this->json('GET', "/api/brands/1/survey-questions/?lang=cat")->assertStatus(400);
    }

    public function testRatingSurveyQuestionAreCreatedCorrectly()
    {

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $surveyCategory = factory(Category::class)->create([
            'brand_id' => $brand->id
        ]);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id
        ]);

        $question = $this->json(
            'POST',
            "/api/brands/" . $brand->id . "/survey-questions/",
            [
                'required' => 1,
                'survey_category_id' => $surveyCategory->id,
                'type' => 'Rating',
                'survey_questions_text' => json_encode([["lang_value" => "en","text" => "Question one"],["lang_value" => "es","text" => "Pregunta uno"]])
            ],
            ['contentType' => 'application/json']
        )
            ->assertStatus(200)
            ->assertJson(["brand_id" => $brand->id, "required" => 1]);

        // Assert that insert question on correct category
        $question = Question::where('id', $question->baseResponse->original->question_id)->first();
        $surveyQuestion = SurveyQuestion::where('question_id', $question->id)->first();
        $this->assertEquals($question->category_id, $surveyCategory->id);
        $this->assertNotEquals($question->category_id, null);

        // Assert that insert all the languages correctly
        $surveyQuestionText = $question->load('questionText')->questionText;
        $englishText = $this->getQuestionText($surveyQuestionText, 'en');
        $spanishText = $this->getQuestionText($surveyQuestionText, 'es');

        $this->assertEquals($englishText, "Question one");
        $this->assertEquals($spanishText, "Pregunta uno");
    }

    public function testMultiresponseSurveyQuestionAreCreatedCorrectly()
    {

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $surveyCategory = factory(Category::class)->create([
            'brand_id' => $brand->id
        ]);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id
        ]);

        $surveyAnswers = [[["lang_value" => "ca", "text" => "Respuesta 1 cat"],["lang_value" => "de", "text" => "Respuesta 1 de"],["lang_value" => "en","text" => "Respuesta 1 en"],["lang_value" => "es", "text" => "Respuesta 1 es"],["lang_value" => "fr","text" => "Respuesta 1 fr"],["lang_value" => "it","text" => "Respuesta 1 it"],["lang_value" => "zh","text" => "Respuesta 1 zh"]],[["lang_value" => "ca","text" => "Respuesta 2 ca"],["lang_value" => "de","text" => "Respuesta 2 de"],["lang_value" => "en","text" => "Respuesta 2 en"],["lang_value" => "es","text" => "Respuesta 2 es"],["lang_value" => "fr","text" => "Respuesta 2 fr"],["lang_value" => "it","text" => "Respuesta 2 it"],["lang_value" => "zh","text" => "Respuesta 2 zh"]]];

        $question = $this->json(
            'POST',
            "/api/brands/" . $brand->id . "/survey-questions/",
            [
                'required' => 1,
                'survey_category_id' => $surveyCategory->id,
                'type' => 'Multiresponse',
                'survey_answers' => json_encode($surveyAnswers),
                'survey_questions_text' => json_encode([["lang_value" => "en","text" => "Question one"],["lang_value" => "es","text" => "Pregunta uno"]])
            ],
            ['contentType' => 'application/json']
        )
            ->assertStatus(200)
            ->assertJson(["brand_id" => $brand->id, "required" => 1]);

        // Assert that insert question on correct category
        $question = Question::where('id', $question->baseResponse->original->question_id)->first();
        $surveyQuestion = SurveyQuestion::where('question_id', $question->id)->first();
        $this->assertEquals($question->category_id, $surveyCategory->id);
        $this->assertNotEquals($question->category_id, null);

        $questionBrand = QuestionBrand::where('question_id', $question->id)->first();
        $this->assertEquals($questionBrand->allow_multiple_responses, 0);

        $questionResponses = QuestionResponse::where('question_id', $question->id)->get()->load('questionResponseText');

        $this->assertEquals(count($questionResponses), count($surveyAnswers));

        foreach ($questionResponses as $index => $questionResponse) {
            foreach ($questionResponse->questionResponseText as $questionResponseText) {
                $responseTranslation = Arr::first($surveyAnswers[$index], function ($surveyAnswer) use ($questionResponseText) {
                    return $surveyAnswer['lang_value'] == Arr::get($questionResponseText, 'lang_value');
                });

                $this->assertEquals(data_get($responseTranslation, 'text'), $questionResponseText->text);
            }
        }
    }

    public function testMultiresponseWithMultipleSelectionSurveyQuestionAreCreatedCorrectly()
    {

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $surveyCategory = factory(Category::class)->create([
            'brand_id' => $brand->id
        ]);

        factory(Survey::class)->create([
            'brand_id' => $brand->id
        ]);

        $surveyAnswers = [[["lang_value" => "ca", "text" => "Respuesta 1 cat"],["lang_value" => "de", "text" => "Respuesta 1 de"],["lang_value" => "en","text" => "Respuesta 1 en"],["lang_value" => "es", "text" => "Respuesta 1 es"],["lang_value" => "fr","text" => "Respuesta 1 fr"],["lang_value" => "it","text" => "Respuesta 1 it"],["lang_value" => "zh","text" => "Respuesta 1 zh"]],[["lang_value" => "ca","text" => "Respuesta 2 ca"],["lang_value" => "de","text" => "Respuesta 2 de"],["lang_value" => "en","text" => "Respuesta 2 en"],["lang_value" => "es","text" => "Respuesta 2 es"],["lang_value" => "fr","text" => "Respuesta 2 fr"],["lang_value" => "it","text" => "Respuesta 2 it"],["lang_value" => "zh","text" => "Respuesta 2 zh"]]];

        $question = $this->json(
            'POST',
            "/api/brands/" . $brand->id . "/survey-questions/",
            [
                'required' => 1,
                'survey_category_id' => $surveyCategory->id,
                'type' => 'Multiresponse',
                'multiple_selection' => "on",
                'survey_answers' => json_encode($surveyAnswers),
                'survey_questions_text' => json_encode([["lang_value" => "en","text" => "Question one"],["lang_value" => "es","text" => "Pregunta uno"]])
            ],
            ['contentType' => 'application/json']
        )
            ->assertStatus(200)
            ->assertJson(["brand_id" => $brand->id, "required" => 1]);

        // Assert that insert question on correct category
        $question = Question::where('id', $question->baseResponse->original->question_id)->first();
        $surveyQuestion = SurveyQuestion::where('question_id', $question->id)->first();
        $this->assertEquals($question->category_id, $surveyCategory->id);
        $this->assertNotEquals($question->category_id, null);

        $questionBrand = QuestionBrand::where('question_id', $question->id)->first();
        $this->assertEquals($questionBrand->allow_multiple_responses, 1);
        $this->assertEquals($questionBrand->required, 1);


        $questionResponses = QuestionResponse::where('question_id', $question->id)->get()->load('questionResponseText');

        $this->assertEquals(count($questionResponses), count($surveyAnswers));

        foreach ($questionResponses as $index => $questionResponse) {
            foreach ($questionResponse->questionResponseText as $questionResponseText) {
                $responseTranslation = Arr::first($surveyAnswers[$index], function ($surveyAnswer) use ($questionResponseText) {
                    return $surveyAnswer['lang_value'] == Arr::get($questionResponseText, 'lang_value');
                });

                $this->assertEquals(data_get($responseTranslation, 'text'), $questionResponseText->text);
            }
        }
    }

    public function testOpenQuestionSurveyQuestionAreCreatedCorrectly()
    {

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $surveyCategory = factory(Category::class)->create([
            'brand_id' => $brand->id
        ]);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id
        ]);

        $question = $this->json(
            'POST',
            "/api/brands/" . $brand->id . "/survey-questions/",
            [
                'required' => 1,
                'survey_category_id' => $surveyCategory->id,
                'type' => 'Open Question',
                'survey_questions_text' => json_encode([["lang_value" => "en","text" => "Question one"],["lang_value" => "es","text" => "Pregunta uno"]])
            ],
            ['contentType' => 'application/json']
        )
            ->assertStatus(200)
            ->assertJson(["brand_id" => $brand->id, "required" => 1]);

        // Assert that insert question on correct category
        $question = Question::where('id', $question->baseResponse->original->question_id)->first();
        $surveyQuestion = SurveyQuestion::where('question_id', $question->id)->first();
        $this->assertEquals($question->category_id, $surveyCategory->id);
        $this->assertNotEquals($question->category_id, null);

        // Assert that insert all the languages correctly
        $surveyQuestionText = $question->load('questionText')->questionText;
        $englishText = $this->getQuestionText($surveyQuestionText, 'en');
        $spanishText = $this->getQuestionText($surveyQuestionText, 'es');

        $this->assertEquals($englishText, "Question one");
        $this->assertEquals($spanishText, "Pregunta uno");
    }

    public function testCreateQuestionWithInvalidDataFails()
    {
        $this->json(
            'POST',
            "/api/brands/1/survey-questions/",
            [
                'required' => 1,
                'survey_category_id' => 1,
                'type' => 'InventType',
                'survey_questions_text' => json_encode([["lang_value" => "en","text" => "Question one"],["lang_value" => "es","text" => "Pregunta uno"]])
            ],
            ['contentType' => 'application/json']
        )->assertStatus(400);
    }

    public function testSurveyQuestionAreSpreadCorrectly()
    {
        $surveyQuestionBrand = $this->prepareSurveyQuestion();

        // Create a parent and sibling brand
        $chain = factory(Cadena::class)->create();
        $parentBrand = Brand::where('chain_id', $chain->id)->first();

        // Prepare sibling brand to spread configuration
        $siblingHotel = factory(Hotel::class)->create();
        $siblingBrand = Brand::where('hotel_id', '=', $siblingHotel->id)->first();
        $siblingSatisfaction = factory(HotelSatisfaction::class)->create([
            "id_hotel" => $siblingHotel->id,
            "customized_chain_activated" => 0
        ]);

        // Update childs brand
        Brand::whereIn('id', [$surveyQuestionBrand->brand_id, $siblingBrand])->update(['parent_id' => $parentBrand->id]);
        factory(CadenaHotel::class)->create([
            'id_cadena'   => $chain->id,
            'id_hotel' => $siblingHotel->id,
        ]);

        $this->json(
            'POST',
            "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/spread/",
            [
                'brands_spread' => json_encode([$siblingBrand->id]),
            ],
            ['contentType' => 'application/json']
        )->assertStatus(204);

        //Asserting questions spread
        $childQuestionsBrand = QuestionBrand::where('brand_id', $surveyQuestionBrand->brand_id)->pluck('question_id')->toArray();
        $parentQuestionsBrand = QuestionBrand::where('brand_id', $parentBrand->id)->pluck('question_id')->toArray();

        $survey = Survey::where('brand_id', $parentBrand->id)->first();
        $surveyQuestion = SurveyQuestion::where('survey_id', $survey->id)->first();

        $this->assertNotEquals($survey, null);
        $this->assertNotEquals($surveyQuestion, null);


        $this->assertEquals(array_diff($childQuestionsBrand, $parentQuestionsBrand), []);
    }

    public function testSurveyQuestionAreUpdatedCorrectly()
    {
        $surveyQuestionBrand = $this->prepareSurveyQuestion();

        $this->json('PUT', "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/" . $surveyQuestionBrand->question_id . "/?required=1&active=0")
            ->assertStatus(204);

        $sq = SurveyQuestion::where('question_id', $surveyQuestionBrand->question_id)->first();

        $this->assertEquals($surveyQuestionBrand->fresh()->required, 1);
        $this->assertEquals($surveyQuestionBrand->fresh()->active, 0);
        $this->assertEquals($sq->required, 1);
        $this->assertEquals($sq->active, 0);
    }

    public function testNormalSurveyQuestionTextIsUpdatedCorrectly()
    {
        $surveyQuestionBrand = $this->prepareSurveyQuestion();

        $englishSurveyQuestionText = factory(QuestionText::class)->create([
            'question_id' => $surveyQuestionBrand->question_id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $this->json(
            'PUT',
            "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/" . $surveyQuestionBrand->question_id,
            [
                'survey_questions_text' => json_encode([
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

        $spanishQuestionText = QuestionText::where([
            'question_id' => $surveyQuestionBrand->question_id,
            'lang_value' => 'es'
        ])->first();

        $this->assertEquals($englishSurveyQuestionText->fresh()->text, "updated en");
        $this->assertEquals($spanishQuestionText->text, "updated es");
    }

    public function testMultiresponseSurveyQuestionTextIsUpdatedCorrectly()
    {
        $surveyQuestionBrand = $this->prepareSurveyQuestion();

        $englishSurveyQuestionText = factory(QuestionText::class)->create([
            'question_id' => $surveyQuestionBrand->question_id,
            'lang_value' => 'en',
            'text' => "Question text test"
        ]);

        $surveyResponse = factory(QuestionResponse::class)->create([
            'question_id' => $surveyQuestionBrand->question_id
        ]);

        $surveyResponse2 = factory(QuestionResponse::class)->create([
            'question_id' => $surveyQuestionBrand->question_id
        ]);

        $surveyResponseTextEn1 = factory(QuestionResponseText::class)->create([
            'question_response_id' => $surveyResponse->id,
            'lang_value' => 'en'
        ]);

        $surveyResponseTextEn2 = factory(QuestionResponseText::class)->create([
            'question_response_id' => $surveyResponse2->id,
            'lang_value' => 'en'
        ]);


        $this->json(
            'PUT',
            "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/" . $surveyQuestionBrand->question_id,
            [
                'survey_questions_text' => json_encode([
                    ["lang_value" => "en","text" => "updated en"],
                    ["lang_value" => "es","text" => "updated es"],
                    ["lang_value" => "ca","text" => "updated ca"],
                    ["lang_value" => "de","text" => "updated de"],
                    ["lang_value" => "fr","text" => "updated fr"],
                    ["lang_value" => "it","text" => "updated it"],
                    ["lang_value" => "zh","text" => "updated zh"],
                ]),
                'survey_answers' => json_encode([
                    [
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "en","text" => "updated 1 en"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "es","text" => "updated 1 es"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "ca","text" => "updated 1 ca"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "de","text" => "updated 1 de"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "fr","text" => "updated 1 fr"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "it","text" => "updated 1 it"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "zh","text" => "updated 1 zh"],
                    ],
                    [
                        ["question_response_id" => $surveyResponse2->id, "lang_value" => "en","text" => "updated 2 en"],
                        ["question_response_id" => $surveyResponse2->id, "lang_value" => "es","text" => "updated 2 es"],
                        ["question_response_id" => $surveyResponse2->id, "lang_value" => "ca","text" => "updated 2 ca"],
                        ["question_response_id" => $surveyResponse2->id, "lang_value" => "de","text" => "updated 2 de"],
                        ["question_response_id" => $surveyResponse2->id, "lang_value" => "fr","text" => "updated 2 fr"],
                        ["question_response_id" => $surveyResponse2->id, "lang_value" => "it","text" => "updated 2 it"],
                        ["question_response_id" => $surveyResponse2->id, "lang_value" => "zh","text" => "updated 2 zh"],
                    ],

                ]),
            ],
            ['contentType' => 'application/json']
        )->assertStatus(204);

        $spanishQuestionText = QuestionText::where([
            'question_id' => $surveyQuestionBrand->question_id,
            'lang_value' => 'es'
        ])->first();

        $questionResponseTextEs1 = QuestionResponseText::where([
            'question_response_id'  => $surveyResponse->id,
            'lang_value'            => 'es'
        ])->first();

        $questionResponseTextEs2 = QuestionResponseText::where([
            'question_response_id'  => $surveyResponse2->id,
            'lang_value'            => 'es'
        ])->first();

        $this->assertEquals($englishSurveyQuestionText->fresh()->text, "updated en");
        $this->assertEquals($spanishQuestionText->text, "updated es");

        $this->assertEquals($surveyResponseTextEn1->fresh()->text, "updated 1 en");
        $this->assertEquals($surveyResponseTextEn2->fresh()->text, "updated 2 en");
        $this->assertEquals($questionResponseTextEs1->text, "updated 1 es");
        $this->assertEquals($questionResponseTextEs2->text, "updated 2 es");
    }

    public function testUpdateQuestionFromAnotherBrandFails()
    {
        $hotel = factory(Hotel::class)->create();
        $anotherBrand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $surveyQuestionBrand = $this->prepareSurveyQuestion();

        $this->json(
            'PUT',
            "/api/brands/" . $anotherBrand->id . "/survey-questions/" . $surveyQuestionBrand->question_id,
            [
                'survey_questions_text' => json_encode([
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

    public function testUpdateQuestionWithoutAllLanguagesFails()
    {
        $surveyQuestionBrand = $this->prepareSurveyQuestion();

        $this->json(
            'PUT',
            "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/" . $surveyQuestionBrand->question_id,
            [
                'survey_questions_text' => json_encode([
                    ["lang_value" => "en","text" => "updated en"],
                    ["lang_value" => "es","text" => "updated es"],
                ])
            ],
            ['contentType' => 'application/json']
        )->assertStatus(400);
    }

    public function testUpdateQuestionResponseWithoutAllLanguagesFails()
    {
        $surveyQuestionBrand = $this->prepareSurveyQuestion();
        $surveyResponse = factory(QuestionResponse::class)->create([
            'question_id' => $surveyQuestionBrand->question_id
        ]);

        $surveyResponse2 = factory(QuestionResponse::class)->create([
            'question_id' => $surveyQuestionBrand->question_id
        ]);


        $this->json(
            'PUT',
            "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/" . $surveyQuestionBrand->question_id,
            [
                'survey_questions_text' => json_encode([
                    ["lang_value" => "en","text" => "updated en"],
                    ["lang_value" => "es","text" => "updated es"],
                    ["lang_value" => "ca","text" => "updated ca"],
                    ["lang_value" => "de","text" => "updated de"],
                    ["lang_value" => "fr","text" => "updated fr"],
                    ["lang_value" => "it","text" => "updated it"],
                    ["lang_value" => "zh","text" => "updated zh"],
                ]),
                'survey_answers' => json_encode([
                    [
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "en","text" => "updated 1 en"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "es","text" => "updated 1 es"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "ca","text" => "updated 1 ca"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "de","text" => "updated 1 de"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "fr","text" => "updated 1 fr"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "it","text" => "updated 1 it"],
                        ["question_response_id" => $surveyResponse->id, "lang_value" => "zh","text" => "updated 1 zh"],
                    ],
                    [
                        ["question_response_id" => $surveyResponse2->id, "lang_value" => "en","text" => "updated 2 en"],
                        ["question_response_id" => $surveyResponse2->id, "lang_value" => "es","text" => "updated 2 es"],
                    ],

                ]),
            ],
            ['contentType' => 'application/json']
        )->assertStatus(400);
    }

    public function testSurveyQuestionAreDeletedCorrectly()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $surveyQuestionBrand = $this->prepareSurveyQuestion();

        $surveyQuestionText = factory(QuestionText::class)->create([
            'question_id' => $surveyQuestionBrand->question_id,
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
            'brand_id' => $brand->id
        ]);

        $this->json('DELETE', "/api/brands/" . $surveyQuestionBrand->brand_id . "/survey-questions/" . $surveyQuestionBrand->question_id . "/")
            ->assertStatus(204);

        $sq = SurveyQuestion::where('question_id', $surveyQuestionBrand->question_id)->first();

        // Assert deleting in cascade
        $this->assertEquals($surveyQuestionBrand->fresh(), null);
        $this->assertEquals($surveyQuestionText->fresh(), null);
        $this->assertEquals($satisfactionAnswer->fresh(), null);
        $this->assertEquals($sq, null);
    }

    private function prepareSurveyQuestion()
    {

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        factory(HotelSatisfaction::class)->create([
            'id_hotel' => $hotel->id,
            'customized_chain_activated' => 0
        ]);

        $surveyCategory = factory(Category::class)->create([
            'brand_id' => $brand->id
        ]);

        $surveyQuestion = factory(Question::class)->create([
            'category_id' => $surveyCategory->id,
        ]);

        $surveyQuestionBrand = factory(QuestionBrand::class)->create([
            'brand_id' => $brand->id,
            'question_id' => $surveyQuestion->id,
            'required' => 0,
            'active' => 1
        ]);

        $survey = factory(Survey::class)->create([
            'brand_id' => $brand->id
        ]);

        factory(SurveyQuestion::class)->create([
            'survey_id' => $survey->id,
            'question_id' => $surveyQuestion->id,
            'required' => 0,
            'active' => 1
        ]);

        return $surveyQuestionBrand;
    }

    private function getQuestionText($questions, $lang)
    {

        $questionTranslation = Arr::first($questions, function ($question) use ($lang) {
            return $question['lang_value'] == $lang;
        });

        return data_get($questionTranslation, 'text');
    }
}
