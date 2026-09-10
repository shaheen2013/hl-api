<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Satisfaction;
use App\Http\Resources\SurveyWidgetResource;
use App\Types\Survey\PutIncidentsDataType;
use App\Types\Survey\PutUserSurveyDataType;
use App\Types\Survey\SurveyExportDataType;
use App\Jobs\ProcessSurveyExport;
use App\Services\SatisfactionService;
use App\Types\Survey\DeleteUserSurveyType;
use Illuminate\Http\Request;
/**
 * @property \Illuminate\Foundation\Application|mixed SatisfactionService
 */
class SatisfactionController extends Controller
{
    private $satisfactionService;
    public function __construct(SatisfactionService $satisfactionService)
    {
        $this->satisfactionProductService = app('App\Services\Products\SatisfactionSurveyProductService');
        $this->satisfactionService = $satisfactionService;
    }

    /**
     * @SWG\Get(
     *     path="/api/brands/{brand_id}/surveys/satisfactions/",
     *     description="Shows the requested satisfaction
    ",
     *     tags={"Satisfaction"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *      @SWG\Parameter(
     *         name="satisfaction_id",
     *         in="path",
     *         type="integer",
     *         description="HL satisfaction id, identifier that will be used to recover a specific survey",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         description="Review lang, first two locale characters (en = english, es = spanish, de = german, etc)",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="score",
     *         in="query",
     *         type="integer",
     *         description="Review min score, rated from 0 (dislike) to 10 (highly recommended)",
     *         required=false,
     *     ),
     *    @SWG\Parameter(
     *         name="days",
     *         in="query",
     *         type="integer",
     *         description="Days to retrieve satisfactions from",
     *         required=false,
     *     ),
     *      *    @SWG\Parameter(
     *         name="limit",
     *         in="query",
     *         type="integer",
     *         description="Days to retrieve satisfactions from",
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
     * @param $brand_id
     * @param $satisfaction_id
     * @param Request $request
     * @return Satisfaction[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getSatisfactions($brand_id, Request $request)
    {
        return response($this->satisfactionProductService->getSurveys($brand_id, $request), '200');
    }

    public function show($id)
    {
        return Satisfaction::where('id', $id)->with('user')->get()->load('user.userFacebook')->load('hotel');
    }

    /**
     * @SWG\Get(
     *     path="/api/satisfactions/{brand_id}/{lang}/{score}/{days}/{exclusiveFavorite}",
     *     description="Shows the first ten reviews following the parameters gave (
    <strong style='color:red'>int</strong> brand_id: identifier that will be used to relate all requested info with a concreted HL brand client data,
    <strong style='color:red'>string</strong> lang: first two locale characters (en = english, es = spanish, de = german, etc),
    <strong style='color:red'>int</strong> min_score: rated from 0 (dislike) to 10 (highly recommended))
    the answer will follow the next structure:
    <strong style='color:blue'>id</strong>: Satisfaction id,
    <strong style='color:blue'>rate</strong>: Satisfaction score,
    <strong style='color:blue'>comment</strong>: Satisfaction comment,
    <strong style='color:blue'>date</strong>: Date when was created the satisfaction quiz,
    <strong style='color:blue'>id_room</strong>: room where was hosted the client when he submit the quiz,
    <strong style='color:blue'>user</strong>: {
    <strong style='color:blue'>id</strong>: HL user id,
    <strong style='color:blue'>email</strong>: user email,
    <strong style='color:blue'>name</strong>: user name,
    <strong style='color:blue'>location</strong>: user locale,
    <strong style='color:blue'>lang</strong>: user lang,
    <strong style='color:blue'>birthday</strong>: user birth date,
    <strong style='color:blue'>gender</strong>: user gender (male or female),
    <strong style='color:blue'>email_result</strong>: evaluation of the validity of the email (Undeliverable, Risky, Deliverable),
    <strong style='color:blue'>unsubscribed</strong>: User subscription to HL notifications 1=yes, 0=no,
    <strong style='color:blue'>user_facebook</strong>: {
    <strong style='color:blue'>name</strong>: user name obtained by facebook,
    <strong style='color:blue'>gender</strong>: user gender obtained by facebook,
    <strong style='color:blue'>age</strong>: user age obtained by facebook,
    <strong style='color:blue'>locationID</strong>: user facebook location id,
    <strong style='color:blue'>locationName</strong>: user location name obtained by facebook,
    <strong style='color:blue'>locale</strong>: user locale obtained by facebook,
    <strong style='color:blue'>facebook_img</strong>: user image url obtained by facebook,
    <strong style='color:blue'>friends</strong>: number of user friends,
    <strong style='color:blue'>birthday</strong>: user birthday obtained by facebook
    }
    }
    }
    ",
     *     tags={"Satisfaction"},
     *     @SWG\Parameter(
     *         name="brand_id",
     *         in="path",
     *         type="integer",
     *         description="HL brand id, identifier that will be used to relate all requested info with a concreted HL brand client data",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="lang",
     *         in="path",
     *         type="string",
     *         description="Review lang, first two locale characters (en = english, es = spanish, de = german, etc)",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="score",
     *         in="path",
     *         type="integer",
     *         description="Review min score, rated from 0 (dislike) to 10 (highly recommended)",
     *         required=true,
     *     ),
     *    @SWG\Parameter(
     *         name="days",
     *         in="path",
     *         type="integer",
     *         description="Days to retrieve satisfactions from",
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
     * @param $brand_id
     * @param $lang
     * @param $score
     * @param $days
     * @param $exclusiveFavorite
     * @param int $limit
     * @return Satisfaction[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function showByBrandIdAndScore($brand_id, $lang, $score = 0, $days = PHP_INT_MIN, $exclusiveFavorite = false, $limit = 10)
    {
        $exclusiveFavorite = $exclusiveFavorite == "true";

        $days = (float) $days;
        $satisfactions = $this->satisfactionProductService->getSatisfactionByLang($lang, $brand_id, $days, $exclusiveFavorite, $score, $limit);

        $satisfactionsInRequestedLang = $satisfactions->count();
        // if there are not enough comments in the selected language, we recover english satisfactions also
        if ($satisfactionsInRequestedLang < $limit && $lang != 'en') {
            $satisfactionsEn = $this->satisfactionProductService->getSatisfactionByLang('en', $brand_id, $days, $exclusiveFavorite, $score, ($limit - $satisfactionsInRequestedLang));
            $satisfactions = $satisfactions->merge($satisfactionsEn);
        }

        return response(SurveyWidgetResource::collection($satisfactions), 200);
    }

    public function setIncidentsToSatisfaction(PutIncidentsDataType $putData)
    {
        if ($putData->isInvalidData()) {
            throw new InvalidRequestException('Bad fields in request body', [
                'inputs' => $putData->getAttributes(),
                'errors' => $putData->getValidatorMessages()
            ], 400);
        }

        return response($this->satisfactionProductService->setIncidentsToSatisfaction($putData), '204');
    }

    public function updateUserSurvey(PutUserSurveyDataType $putData)
    {
        if ($putData->isInvalidData()) {
            throw new InvalidRequestException('Bad fields in request body', [
                'inputs' => $putData->getAttributes(),
                'errors' => $putData->getValidatorMessages()
            ], 400);
        }

        return response($this->satisfactionProductService->updateUserSurvey($putData), '204');
    }

    public function queueReport($brand_id, SurveyExportDataType $data)
    {
        if ($data->isInvalidData()) {
            throw new InvalidRequestException('Bad fields in request body', $data->getAttributes(), 400);
        }

        ProcessSurveyExport::dispatch($brand_id, $data)->onQueue('reports');

        return response(null, 202);
    }

    public function deleteUserSurvey(DeleteUserSurveyType $request)
    {
        if (!$request->isInvalidData()) {
            $this->satisfactionService->deleteUserSurvey($request->brandId, $request->userSurveyId, $request->wq);
            return response(null, 204);
        } else {
            throw new InvalidRequestException('Bad fields in request body', [
                'inputs' => $request->getAttributes(),
                'errors' => $request->getValidatorMessages()
            ], 400);
        }
    }
}
