<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Exceptions\SurveyQuestion\SurveyQuestionException;
use App\Question;
use App\QuestionText;
use App\QuestionBrand;
use App\HotelSatisfaction;
use App\SurveyQuestion;
use App\UserSurveyQuestionAnswer;
use App\Services\Surveys\SurveyQuestionService;
use App\Types\Surveys\StoreSurveyQuestionDataType;
use App\Types\Survey\ShowSurveyQuestionDataType;
use App\Types\Survey\UpdateQuestionDataType;
use App\Exceptions\InvalidRequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SurveyQuestionController extends Controller
{
    protected $surveyQuestionService;

    public function __construct(SurveyQuestionService $surveyQuestionService)
    {
        $this->surveyService = app('App\Services\SatisfactionService');
        $this->CustomizedSatisfactionService = app('App\Services\Products\CustomizedSurveyProductService');
        $this->surveyQuestionService = $surveyQuestionService;
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{brand_id}/survey-questions/",
     *     description="Shows all survey-questions of brand or return a specific one following this parameters:<br>
    <strong style='color:red'>int</strong> brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>int</strong> survey_question_id: relates to an specific survey-question (optional),
    <strong style='color:red'>string</strong> lang: first two locale characters (en = english, es = spanish, de = german, etc) (optional)
    <h3>The answer will follow the next structure:</h3>
    [{
    <strong style='color:blue'>id</strong>: Survey question id,
    <strong style='color:blue'>brand_id</strong>: Brand id,
    <strong style='color:blue'>survey_question_id</strong>: Survey question id,
    <strong style='color:blue'>required</strong>: Question is mandatory to be answered 1=yes 0=no,
    <strong style='color:blue'>survey_question</strong>: [{
    <strong style='color:blue; margin-left:.5em'>id</strong>: Survey question id,
    <strong style='color:blue; margin-left:.5em'>survey_category_id</strong>: Survey category id,
    <strong style='color:blue; margin-left:.5em'>survey_question_text</strong>: [{
    <strong style='color:blue; margin-left:1em'>id</strong>: Survey question text id,
    <strong style='color:blue; margin-left:1em'>survey_question_id</strong>: Survey question id,
    <strong style='color:blue; margin-left:1em'>lang_value</strong>: Value of translation (en = english, es= spanish),
    <strong style='color:blue; margin-left:1em'>text</strong>: text of question translation
    }]
    <strong style='color:blue; margin-left:.5em'>survey_category</strong>: {
    <strong style='color:blue; margin-left:1em'>id</strong>: Survey category id,
    <strong style='color:blue; margin-left:1em'>brand_id</strong>: Brand id,
    <strong style='color:blue; margin-left:1em'>survey_category_text</strong>: [{
    <strong style='color:blue; margin-left:1.5em'>id</strong>: Survey category text id,
    <strong style='color:blue; margin-left:1.5em'>survey_category_id</strong>: Survey category id,
    <strong style='color:blue; margin-left:1.5em'>lang_value</strong>: Value of translation (en = english, es= spanish),
    <strong style='color:blue; margin-left:1.5em'>text</strong>: text of category translation
    }]
    }
    }]",
     *     tags={"Survey Question"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="survey_question_id",
     *         in="query",
     *         type="integer",
     *         description="Survey question id, identifier of a sppecific question",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         description="Review lang, first two locale characters (en = english, es = spanish, de = german, etc)",
     *         required=false,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     * Display the specified resource.
     * @param Request $request
     * @param $brand_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(ShowSurveyQuestionDataType $request)
    {
        if (!$request->isInvalidData()) {
            $surveyQuestions = $this->CustomizedSatisfactionService->getQuestions($request->brandId, $request->lang, $request->survey_question_id, $request->active);
            return response($surveyQuestions, 200);
        }

        Log::error('ShowSurveyQuestionDataType validation error', ['payload' => $request->getAttributes(), "errors" => $request->getErrors()]);
        throw new InvalidRequestException('Bad fields in request body', $request->getErrors()->toArray(), 400);
    }

    /**
     * @SWG\Post(
     *     path="/api/brands/{brand_id}/survey-questions/",
     *     description="Create new survey question and their translations following this parameters:<br>
    <strong style='color:red'>int</strong> brand_id: brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>string</strong> required: parameter to set mandatory or not a question (1=yes, 0=no)
    <strong style='color:red'>string</strong> survey_category_id: : Survey category identifier
    <strong style='color:red'>string</strong> survey_questions_text: Stringify array of the translation of the question, following this structure: <br> [{
    <strong style='color:#FFA07A ; margin-left:1em'>lang_value</strong>: Value of translation (en = english, es= spanish),
    <strong style='color:#FFA07A; margin-left:1em'>text</strong>: The text of the question,
    }]
    <h3>The answer will follow the next structure:</h3>
    {
    <strong style='color:blue'>id</strong>: Survey question identifier has just created,
    <strong style='color:blue'>brand_id</strong>: Brand id,
    <strong style='color:blue'>survey_question_id</strong>: Survey question id,
    <strong style='color:blue'>required</strong>: Question is mandatory to be answered 1=yes 0=no,
    }",
     *     tags={"Survey Question"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="survey_category_id",
     *         in="query",
     *         type="integer",
     *         description="The survey category that belongs the inserted question",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="required",
     *         in="query",
     *         type="integer",
     *         description="Flag to set mandatory or not a question (0 = no, 1 = yes)",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="survey_questions_text",
     *         in="query",
     *         type="string",
     *         description="An array of the translation of the question",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     * Display the specified resource.
     * @param Request $request
     * @param $brand_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store($brand_id, StoreSurveyQuestionDataType $data)
    {
        if ($data->isInvalidData()) {
            throw new InvalidRequestException('Invalid request data', [
                'inputs' => $data->getAttributes(),
                'errors' => $data->getValidatorMessages()
            ]);
        }

        return response($this->surveyQuestionService->store($brand_id, $data), 200);
    }

    /**
     * @SWG\Post(
     *     path="/api/brands/{brand_id}/survey-questions/spread",
     *     description="Spread all the questions of specific brand to the selected hotels of same brand following this parameters:<br>
    <strong style='color:red'>int</strong> brand_id: brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>string</strong> brands_to_spread: Stringify array of child brands id <br>
    <h3>The server will process your request without answer (status code 204)</h3>",
     *     tags={"Survey Question"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="brands_to_spread",
     *         in="query",
     *         type="string",
     *         description="An array of the translation of the question",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     * Display the specified resource.
     * @param Request $request
     * @param $brand_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function spread(Request $request, $brand_id)
    {

        $brand = Brand::find($brand_id);
        $parentBrand = Brand::findOrFail($brand->parent_id);

        if ($brand->parent_id) {
            $brandQuestions = QuestionBrand::where('brand_id', $brand_id)->get()->toArray();
            $brandCategories = Category::where('brand_id', $brand_id)->get()->toArray();
            $survey = $this->surveyService->createSurvey($brand->parent_id, "Chain Survey");
            // Spread questions to parent brand
            array_map(function ($question) use ($brand, $survey) {
                unset($question['id']);
                $parentQuestion = data_set($question, 'brand_id', $brand->parent_id);

                \DB::transaction(function () use ($parentQuestion, $survey) {
                    QuestionBrand::firstOrCreate($parentQuestion);
                    $this->surveyService->updateOrCreateSurveyQuestion($survey->id, Arr::get($parentQuestion, 'question_id'), Arr::get($parentQuestion, 'required'), Arr::get($parentQuestion, 'active'));
                });
            }, $brandQuestions);
        }

        $hotelsID = Arr::pluck(Brand::where('parent_id', $brand->parent_id)->get()->load('hotel'), 'hotel_id');
        HotelSatisfaction::whereIn('id_hotel', $hotelsID)->update(['childBrandQuestionsID' => $brand_id]);

        return response(null, 204);
    }

    /**
     * @SWG\Put(
     *     path="/api/brands/{brand_id}/survey-questions/{survey_question_id}",
     *     description="Update required flag of a specific survey question:<br>
    <strong style='color:red'>int</strong> brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>int</strong> survey_question_id: relates to an specific survey-question,
    <strong style='color:red'>string</strong> required: parameter to set mandatory or not a question
    <h3>The server will process delete method without response (status code 204)</h3>",
     *     tags={"Survey Question"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="survey_question_id",
     *         in="path",
     *         type="integer",
     *         description="Survey question id, identifier of a sppecific question",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="required",
     *         in="query",
     *         type="string",
     *         description="Flag to set mandatory or not a question (0 = no, 1 = yes)",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     * Display the specified resource.
     * @param Request $request
     * @param $brand_id
     * @param $survey_question_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UpdateQuestionDataType $request)
    {
        if (!$request->isInvalidData()) {
            $brandId = $request->brandId;
            $questionId = $request->questionId;
            $required = $request->required;
            $active = $request->active;

            if (!is_null($active) && !is_null($required)) {
                $surveyQuestion = QuestionBrand::where([
                    'question_id'   => $questionId,
                    'brand_id'      => $brandId
                ])->update([
                    'required'      => $required,
                    'active'        => $active
                ]);

                $survey = $this->surveyService->getSurvey($brandId);
                if ($survey) {
                    $this->surveyService->updateOrCreateSurveyQuestion($survey->id, $questionId, $required, $active);
                }
            }

            if ($request->surveyQuestionsText) {
                $this->surveyQuestionService->updateQuestionTexts($request);
            }

            return response(null, 204);
        }

        Log::error('UpdateQuestionDataType validation error', ['payload' => $request->getAttributes(), "errors" => $request->getErrors()]);
        throw new InvalidRequestException('Bad fields in request body', $request->getErrors()->toArray(), 400);
    }

    /**
     * @SWG\Delete(
     *     path="/api/brands/{brand_id}/survey-questions/{survey_question_id}",
     *     description="Delete a specific survey question<br>
    <strong style='color:red'>int</strong> brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>int</strong> survey_question_id: relates to an specific survey-question,
    <strong style='color:red'>string</strong> required: parameter to set mandatory or not a question
    <h3>The server will process delete method without response (status code 204)</h3>",
     *     tags={"Survey Question"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="survey_question_id",
     *         in="path",
     *         type="integer",
     *         description="Survey question id, identifier of a sppecific question",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     * Display the specified resource.
     * @param $brand_id
     * @param $survey_question_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete($brand_id, $question_id)
    {
        $surveyQuestionBrand = QuestionBrand::where(['question_id' => $question_id, 'brand_id' => $brand_id])->first();

        if ($surveyQuestionBrand) {
            try {
                $question = Question::where(['id' => $question_id])->first();

                $surveyQuestion = SurveyQuestion::where('question_id', $question_id)
                ->select('id')
                ->first();

                if ($surveyQuestion) {
                    UserSurveyQuestionAnswer::where(['survey_question_id' => $surveyQuestion->id])->delete();
                }

                if ($question) {
                    $deletedPosition = $question->position;
                    $categoryId = $question->category_id;
                    $question->delete();
                    $this->surveyQuestionService->decrementPositions($deletedPosition, $categoryId);
                }
            } catch (\Exception $e) {
                throw new SurveyQuestionException("Could not delete the Question", [], 409);
            }
        }
        return response([], 204);
    }
}
