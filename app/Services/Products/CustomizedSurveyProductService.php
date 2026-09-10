<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 15/03/2019
 * Time: 09:49
 */

namespace App\Services\Products;

use App\Brand;
use App\Http\Resources\ProductsConfigurations\CustomizedSurveyProductConfiguration;
use App\Category;
use App\CategoryText;
use App\Question;
use App\QuestionBrand;
use App\QuestionText;
use App\SatisfactionAnswer;
use App\HotelSatisfaction;
use App\Http\Resources\Surveys\SurveyQuestionsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CustomizedSurveyProductService implements HLProduct
{
    private $satisfaction;

    public function __construct()
    {
        $this->satisfaction = app('App\Services\Products\SatisfactionSurveyProductService');
        $this->satisfactionService = app('App\Services\SatisfactionService');
        $this->surveyCategoryService = app('App\Services\Surveys\SurveyCategoryService');
        $this->surveyQuestionService = app('App\Services\Surveys\SurveyQuestionService');
    }

    public function getConfiguration($brand_id)
    {
        $brand = Brand::findOrFail($brand_id)->load('hotel');
        return new CustomizedSurveyProductConfiguration($brand);
    }

    public function setConfiguration($brand_id, Request $request)
    {
        $brand = Brand::findOrFail($brand_id)->load('hotel');
        $hotel = $brand->hotel;

        $categoryOrder = json_decode($request->input('categoryOrder'), true);
        $questionOrder = json_decode($request->input('questionOrder'), true);

        if ($hotel->hotelSatisfaction) {
            $comment = json_decode($request->customizedComment, true) ?? $request->customizedComment;

            $hotel->hotelSatisfaction->customized_active = $request->customizedActive;
            $hotel->hotelSatisfaction->customized_type = $request->customizedType;
            $hotel->hotelSatisfaction->customized_send_days = $request->customizedSendDays;
            $hotel->hotelSatisfaction->customized_send_hours = $request->customizedSendHours;
            $hotel->hotelSatisfaction->customized_chain_activated = $request->customizedChainActivated;
            $hotel->hotelSatisfaction->customized_comment = is_string($comment) ? $comment : $request->customizedComment;
            $hotel->hotelSatisfaction->customized_warning_emails = $request->customizedWarning;

            $hotel->hotelSatisfaction->save();

            $targetBrandId = $request->customizedChainActivated && $brand->parent_id ? $brand->parent_id : $brand->id;

            if ($request->customizedChainActivated && $brand->parent_id) {
                $survey = $this->satisfactionService->createSurvey($brand->parent_id, "Chain Survey");
            } else {
                $survey = $this->satisfactionService->getSurvey($brand->id);
            }
          
            $this->satisfactionService->updateNotAnsweredUserSurvey($brand->id, $survey->id);

            if (!empty($categoryOrder)) {
                $this->surveyCategoryService->updateOrder($targetBrandId, $categoryOrder);
            }

            if (!empty($questionOrder)) {
                $this->surveyQuestionService->updateOrder($questionOrder);
            }
        }

        return new CustomizedSurveyProductConfiguration($brand);
    }

    public function setDefaultConfiguration($brand_id, $active)
    {
        $brandSurveyCategories = Category::where('brand_id', $brand_id)->get();
        $this->satisfaction->setDefaultConfiguration($brand_id, $active);
        if ($brandSurveyCategories->count() == 0) {
            $surveyConfigurations = config('customized_surveys.default_configuration.default_questions_and_categories');
            foreach ($surveyConfigurations as $surveyConfiguration) {
                $surveyCategory = Category::create(
                    [
                        'brand_id' => $brand_id
                    ]
                );
                $surveyQuestion = Question::create(
                    [
                        'category_id' => $surveyCategory->id
                    ]
                );
                QuestionBrand::create(
                    [
                        'brand_id' => $brand_id,
                        'question_id' => $surveyQuestion->id,
                        'required' => 1
                    ]
                );
                foreach ($surveyConfiguration as $lang => $surveyCategoryText) {
                    CategoryText::firstOrCreate([
                        'category_id' => $surveyCategory->id,
                        'lang_value' => $lang,
                        'text' => Arr::get($surveyCategoryText, 'category')
                    ]);

                    QuestionText::firstOrCreate([
                        'question_id' => $surveyQuestion->id,
                        'lang_value' => $lang,
                        'text' => Arr::get($surveyCategoryText, 'question')
                    ]);
                }
            }
        }
    }

    public function getQuestions($brand_id, $lang = null, $surveyQuestionID = null, $active = null)
    {

        $brand = Brand::where('id', $brand_id)->first();
        $hotelSatisfaction = HotelSatisfaction::where('id_hotel', $brand->hotel_id)->first();

        if (data_get($hotelSatisfaction, "customized_chain_activated") == 1) {
            $brand_id = $brand->parent_id;
        }

        $surveyQuestions = Category::with([
            'question' => function ($q) use ($lang, $brand_id, $active) {
                $q->with(['questionBrand' => function ($q) use ($brand_id, $active) {
                    $q->where('brand_id', $brand_id);
                }])
                ->with([
                    'questionText' => function ($q) use ($lang) {
                        if ($lang) {
                            $langRequested = $q->where('lang_value', $lang);
                            if ($langRequested->get()->isEmpty()) {
                                $q->orWhere('lang_value', 'en');
                            }
                        }
                    }
                ])
                ->with([
                    'questionResponse' => function ($q) use ($lang) {
                        $q->with([
                            'questionResponseText' => function ($q) use ($lang) {
                                if ($lang) {
                                    $langRequested = $q->where('lang_value', $lang);
                                    if ($langRequested->get()->isEmpty()) {
                                        $q->orWhere('lang_value', 'en');
                                    }
                                }
                            }
                        ]);
                    }
                ])->orderBy('position');
            },
            'categoryText' => function ($q) use ($lang) {
                if ($lang) {
                    $langRequested = $q->where('lang_value', $lang);
                    if ($langRequested->get()->isEmpty()) {
                        $q->orWhere('lang_value', 'en');
                    }
                }
            }])->orderBy('position');

        if ($surveyQuestionID) {
            $surveyQuestions->where('survey_question_id', $surveyQuestionID);
        }

        if (data_get($hotelSatisfaction, "customized_chain_activated") == 1) {
            $surveyQuestions
                ->where('brand_id', $hotelSatisfaction->childBrandQuestionsID)
                ->orWhere('brand_id', $brand_id);
        } else {
            $surveyQuestions->where('brand_id', $brand_id);
        }

        return SurveyQuestionsResource::collection($surveyQuestions->take(50)->get());
    }

    public function getAnswers($brandID, $surveyID)
    {
        $satisfactionAnswers = SatisfactionAnswer::where([
            'brand_id' => $brandID,
            "user_satisfaction_id" => $surveyID
        ])->get();

        return $satisfactionAnswers;
    }
}
