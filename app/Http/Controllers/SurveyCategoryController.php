<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\CategoryText;
use App\HotelSatisfaction;
use App\Services\Surveys\SurveyCategoryService;
use App\Types\Survey\UpdateCategoryDataType;
use App\Http\Resources\SurveyCategoryResource;
use App\Exceptions\InvalidRequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SurveyCategoryController extends Controller
{
    protected $surveyCategoryService;

    public function __construct(SurveyCategoryService $surveyCategoryService)
    {
        $this->surveyCategoryService = $surveyCategoryService;
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{brand_id}/surveys/satisfactions/categories/",
     *     description="Shows all survey-category of brand or return a specific one following this parameters:<br>
    <strong style='color:red'>int</strong> brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>int</strong> survey_category_id: relates to an specific survey-category (optional),
    <strong style='color:red'>string</strong> lang: first two locale characters (en = english, es = spanish, de = german, etc) (optional)
    <h3>The answer will follow the next structure:</h3>
    [{
    <strong style='color:blue'>id</strong>: Survey cateogry id,
    <strong style='color:blue'>brand_id</strong>: Brand id,
    <strong style='color:blue'>survey_category_text</strong>: [{
        <strong style='color:blue; margin-left:.5em'>id</strong>: Survey question id,
        <strong style='color:blue; margin-left:.5em'>survey_category_id</strong>: Survey category id,
        <strong style='color:blue; margin-left:.5em'>lang_value</strong>: Value of translation (en = english, es= spanish),
        <strong style='color:blue; margin-left:.5em'>text</strong>: text of category translation
    }]
    }]",
     *     tags={"Survey Category"},
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
     *         description="Survey category id, identifier of a sppecific question",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         description="Category lang, first two locale characters (en = english, es = spanish, de = german, etc)",
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
     * @return
     */
    public function show(Request $request, $brand_id)
    {
        $surveyCategoryID = $request->get('survey_category_id');
        $lang = $request->get('lang');

        $surveyQuestions = Category::with(['categoryText' => function ($q) use ($lang) {
            if ($lang) {
                $q->where('lang_value', $lang);
            }
        }])->where('brand_id', $brand_id);

        if ($surveyCategoryID) {
            $surveyQuestions->where('question_id', $surveyCategoryID);
        }

        return response(SurveyCategoryResource::collection($surveyQuestions->get()), 200);
    }

    /**
     * @SWG\Post(
     *     path="/api/brands/{brand_id}/surveys/satisfactions/categories/",
     *     description="Create new survey category and their translations following this parameters:<br>
    <strong style='color:red'>int</strong> brand_id: brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>string</strong> survey_category_text: Stringify array of the translation of the question, following this structure: <br> [{
    <strong style='color:#FFA07A ; margin-left:1em'>lang_value</strong>: Value of translation (en = english, es= spanish),
    <strong style='color:#FFA07A; margin-left:1em'>text</strong>: The text of the category,
    }]
    <h3>The answer will follow the next structure:</h3>
    {
    <strong style='color:blue'>id</strong>: Survey question identifier has just created,
    <strong style='color:blue'>brand_id</strong>: Brand id,
    }",
     *     tags={"Survey Category"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="survey_category_text",
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
     * @return
     */
    public function store(Request $request, $brand_id)
    {
        $surveyCategoryID = $request->get('survey_category_id');
        $surveyCategoryText = json_decode($request->get('survey_category_text'), true);

        $lastPosition = Category::where('brand_id', $brand_id)->max('position');
        $newPosition = $lastPosition + 1;

        $surveyCategory = Category::create([
            'brand_id' => $brand_id,
            'position' => $newPosition
        ]);

        // Add survey question id on the array of question translations
        $surveyCategoryText = array_map(function ($categoryText) use ($surveyCategory) {
            return data_set($categoryText, 'category_id', $surveyCategory->id);
        }, $surveyCategoryText);

        // Insert questions translations in bulk
        CategoryText::insert($surveyCategoryText);

        return response($surveyCategory, 200);
    }

    public function update(UpdateCategoryDataType $request)
    {
        if (!$request->isInvalidData()) {
            $this->surveyCategoryService->updateCategoryTexts($request);
            return response(null, 204);
        }

        Log::error('UpdateCategoryDataType validation error', ['payload' => $request->getAttributes(), "errors" => $request->getErrors()]);
        throw new InvalidRequestException('Bad fields in request body', $request->getErrors()->toArray(), 400);
    }

    /**
     * @SWG\Delete(
     *     path="/api/brands/{brand_id}/surveys/satisfactions/categories/{survey_question_id}",
     *     description="Delete a specific survey category<br>
    <strong style='color:red'>int</strong> brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>int</strong> survey_category_id: relates to an specific survey-category,
    <h3>The server will process delete method without response (status code 204)</h3>",
     *     tags={"Survey Category"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="survey_cateogry_id",
     *         in="path",
     *         type="integer",
     *         description="Survey category id, identifier of a specific question",
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
     * @param $survey_category_id
     * @return
     */
    public function delete($brand_id, $survey_category_id)
    {
        $brand = Brand::find($brand_id);
        $surveyCategory = Category::where(['id' => $survey_category_id, 'brand_id' => $brand_id]);
       
        $deletedPosition = $surveyCategory->first()->position;

        if (!$brand->hotel_id) {
            $hotelID = Arr::get($brand->children, '0.hotel_id');
            $hotelSatisfaction = HotelSatisfaction::where('id_hotel', $hotelID)->first();

            $surveyCategory->orWhere(function ($query) use ($survey_category_id, $hotelSatisfaction) {
                $query->where(['id' => $survey_category_id, 'brand_id' => $hotelSatisfaction->childBrandQuestionsID]);
            });
        }

        $surveyCategory->delete();

        $this->surveyCategoryService->decrementPositions($brand_id, $deletedPosition);

        return response(null, 204);
    }
}
