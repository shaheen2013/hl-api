<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Question;
use App\QuestionText;
use App\QuestionBrand;
use App\Types\Survey\SurveyAnswersDataType;
use App\Types\Survey\SaveSurveyAnswersDataType;
use App\Services\SatisfactionService;
use App\Jobs\ProcessSaveSurveyAnswer;
use Illuminate\Http\Request;
use App\Http\Resources\UserSurveyQuestionAnswer;
use App\Http\Resources\UserSurveyQuestionAnswerExternal;
use App\Http\Resources\UserSurveyQuestionAnswerPaginate;
use App\Http\Resources\UserSurveyQuestionAnswerPaginateExternal;
use App\Exceptions\InvalidRequestException;
use Illuminate\Support\Facades\Validator;
use App\Rules\PositiveIntegerListRule;
use App\Services\Visits\VisitService;
use App\Repositories\Visits\UserBrandRepository;

class SurveyAnswerController extends Controller
{
    private $maxPageItemsPaginate;

    public function __construct(SatisfactionService $satisfactionService)
    {
        $this->satisfactionService = $satisfactionService;
        $this->maxPageItemsPaginate = config('services.surveys.paginate.max', 500);
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{brand_id}/surveys/satisfactions/answers",
     *     description="Paginate all surveys answers of brand applyng a list of filters:<br>
     *     tags={"Survey Answers"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         type="integer",
     *         description="Current page to paginate",
     *         required=false,
     *     ),
     *      @SWG\Parameter(
     *         name="page_items",
     *         in="query",
     *         type="integer",
     *         description="Number of items per page. (defautl: 100, limit: 500)",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="brands",
     *         in="query",
     *         type="string",
     *         description="List of brand ids to be used on search",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="language",
     *         in="query",
     *         type="string",
     *         description="Review lang, first two locale characters (en = english, es = spanish, de = german, etc, default: en)",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="category",
     *         in="query",
     *         type="string",
     *         description="Category of the survey",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="favorite",
     *         in="query",
     *         type="bool",
     *         description="Only get the answers marked ",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="date_from",
     *         in="query",
     *         type="string",
     *         description="Only get the answers marked ",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="date_to",
     *         in="query",
     *         type="string",
     *         description="Only get the answers marked ",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="score",
     *         in="query",
     *         type="number",
     *         description="Only get the answers marked ",
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
     *
     * @param int $brand_id
     * @param Request $request
     * @param App\Types\Survey\SurveyAnswersDataType $surveyAnswersDataType
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Request $request, int $brand_id, SurveyAnswersDataType $surveyAnswersDataType, VisitService $visitService, UserBrandRepository $userBrandRepository)
    {
        // Get the flag if is external or not
        $isExternalApi = $request->get('externalApi') ?? false;

        if ($surveyAnswersDataType->isInvalidData()) {
            throw new InvalidRequestException(null, [
                'inputs' => $surveyAnswersDataType->getAttributes(),
                'errors' => $surveyAnswersDataType->getValidatorMessages()
            ]);
        }

        // Get surveys answers
        $surveyAnswers = $this->satisfactionService->getSurveysAnswers($brand_id, $surveyAnswersDataType);

        if ($isExternalApi) {
            return response(new UserSurveyQuestionAnswerPaginateExternal($surveyAnswers, $visitService, $userBrandRepository), 200);
        } else {
            return response(new UserSurveyQuestionAnswerPaginate($surveyAnswers), 200);
        }
    }

    public function saveAnswer(SaveSurveyAnswersDataType $data)
    {
        if ($data->isInvalidData()) {
            throw new InvalidRequestException('Bad fields in request body', $data->getAttributes(), 400);
        }

        ProcessSaveSurveyAnswer::dispatch($data);

        return response(null, 202);
    }
}
