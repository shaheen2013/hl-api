<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 18/03/2019
 * Time: 10:07
 */

namespace App\Services\Products;

use App\Brand;
use App\BrandProduct;
use App\Hotel;
use App\HotelSatisfaction;
use App\Http\Resources\SatisfactionSurveyPaginate;
use App\Http\Resources\ProductsConfigurations\SatisfactionSurveyProductConfiguration;
use App\Product;
use App\Satisfaction;
use App\User;
use App\Services\SatisfactionService;
use App\Repositories\Satisfactions\IncidentRepositoryInterface;
use App\Repositories\Satisfactions\SurveyRepository;
use App\Repositories\Satisfactions\UserSurveyRepository;
use App\Repositories\Visits\UserBrandRepository;
use App\Services\Visits\VisitService;
use App\Types\Survey\GetSurveyDataType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Traits\Filters;
use App\Types\Survey\PutIncidentsDataType;
use App\Types\Survey\PutUserSurveyDataType;
use Illuminate\Support\Facades\Log;

class SatisfactionSurveyProductService implements HLProduct
{
    use Filters;

    private $review;
    private $satisfactionService;
    private $surveyRepository;
    private $incidentRepository;
    private $userSurveyRepository;
    private $visitService;
    private $userBrandRepository;

    public function __construct(SatisfactionService $satisfactionService, SurveyRepository $surveyRepository, IncidentRepositoryInterface $incidentRepository, UserSurveyRepository $userSurveyRepository, VisitService $visitService, UserBrandRepository $userBrandRepository)
    {
        $this->incidentRepository = $incidentRepository;
        $this->satisfactionService = $satisfactionService;
        $this->surveyRepository = $surveyRepository;
        $this->userSurveyRepository = $userSurveyRepository;
        $this->review = app('App\Services\Products\ReviewProductService');
        $this->visitService = $visitService;
        $this->userBrandRepository = $userBrandRepository;
    }

    public function getLastUserSatisfaction($userId, $hotelId)
    {
        return Satisfaction::where([
            ["id_usuario", $userId],
            ["id_hotel", $hotelId],
            ["fecha_creado", '>', Carbon::now()->subDays(1)]
        ])->orderBy('id', "DESC")->first();
    }

    public function getSurvey($brand_id, $satisfaction_id)
    {
        $brand = Brand::find($brand_id);
        $brand_type_id = ($brand->hotel_id) ? $brand->hotel_id : $brand->chain_id;
        $brand_type = ($brand->hotel_id) ? 'id_hotel' : 'id_cadena';

        return Satisfaction::where("$brand_type", $brand_type_id)->where('id', $satisfaction_id)->first()
            ->load(
                'user',
                'user.userFacebook',
                'satisfactionAnswer',
                'satisfactionAnswer.question',
                'satisfactionAnswer.question.category',
                'satisfactionAnswer.question.category.categoryText'
            );
    }

    /**
     * @param $lang
     * @param $brand_id
     * @param $days
     * @param string $brand_type
     * @param int $score
     * @param int $limit
     * @return Satisfaction[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getSatisfactionByLang($lang, $brand_id, $days, $exclusiveFavorite, $brand_type = 'hotel_id', $score = 0, $limit = 10)
    {
        return $this->satisfactionService->getSatisfactionByLang($lang, $brand_id, $days, $exclusiveFavorite, $brand_type, $score, $limit);
    }

    /**
     * @param $brand_id
     * @param $request
     * @param null $satisfaction_id
     * @return mixed
     */
    public function getSurveys($brand_id, $request)
    {
        $brand = Brand::find($brand_id);
        $brand_type = ($brand->hotel_id) ? 'id_hotel' : 'id_cadena';
        $brand_type_id = ($brand->hotel_id) ? $brand->hotel_id : $brand->chain_id;
        $averageScore = null;

        $data = new GetSurveyDataType($request, $brand_type, $brand_type_id);

        $satisfactions = $this->surveyRepository->getSurveys($data);

        if (isset($request->page) && isset($request->itemsPerPage)) {
            $averageScore = $satisfactions->avg('puntuacion');
            $satisfactions = $satisfactions->paginate($request->itemsPerPage);
        } else {
            $satisfactions = $satisfactions->get();
        }

        $satisfactions = new SatisfactionSurveyPaginate($satisfactions, $averageScore, $this->visitService, $this->userBrandRepository);
        return $satisfactions;
    }

    /**
     * @param $brand_id
     * @param Request $request
     * @return SatisfactionSurveyProductConfiguration
     */
    public function setConfiguration($brand_id, Request $request)
    {
        $brand = Brand::findOrFail($brand_id)->load('hotel');
        $hotel = $brand->hotel;

        $hotelSatisfactionData = [
            'diasEnvio'            => $request->sendAfterDays,
            'send_hour'            => $request->sendHour,
            'puntMin'              => $request->puntMin,
            'warning_email'        => $request->warningEmail,
            'sendThanksMail'       => $request->sendThanksMail,
            'sendToNonCustomers'   => $request->sendToNonCustomers,
            'total_followup_email' => $request->totalFollowupEmail,
            'filter_warning'       => $request->filterWarning,
            'force_comment'        => $request->forceComment,
            'review_average_score' => $request->reviewAverageScore,
            'default_score'        => $request->defaultScore
        ];

        HotelSatisfaction::updateOrCreate(['id_hotel' => $hotel->id], $hotelSatisfactionData);

        if ($request->chainEmail && $request->parentID) {
            $this->setChainEmail($request->parentID, $request->warningEmail);
        }

        return new SatisfactionSurveyProductConfiguration($brand);
    }

    /**
     * @param $brand_id
     * @return null
     */
    public function getConfiguration($brand_id)
    {
        $brand = Brand::findOrFail($brand_id);

        return new SatisfactionSurveyProductConfiguration($brand);
    }

    /**
     * @param $brand_id
     * @param $active /
     * @return null
     */
    public function setDefaultConfiguration($brand_id, $active)
    {
        $brand = Brand::findOrFail($brand_id);
        $hotel = $brand->hotel;

        HotelSatisfaction::firstOrCreate(
            ['id_hotel' => $hotel->id],
            ['diasEnvio' => 15, 'puntMin' => 5, 'review_average_score' => 6]
        );

        $this->review->setDefaultConfiguration($brand_id, $active);

        $survey = $this->satisfactionService->createSurvey($brand_id, "Default Survey");
    }

    /**
     * @param $user
     * @param $brand
     * @return bool|string
     */
    public function createSatisfactionUrl($user, $brand)
    {
        $userSatisfaction = $this->getLastUserSatisfaction($user->id, $brand->hotel_id);

        if ($userSatisfaction) {
            $surveyInfo = '{"brand_id" : ' . $brand->id . ', "survey_id" : ' . $userSatisfaction->id . '}';
            $surveyInfo = base64_encode($surveyInfo);
            $url = config('services.surveys.url') . '/survey/' . $surveyInfo;

            return $url;
        }

        return false;
    }

    /**
     * @param $brand_id
     * @param $warning_email
     */
    private function setChainEmail($brand_id, $warning_email)
    {
        $brand = Brand::findOrFail($brand_id);

        $warningEmails = [];
        foreach ($brand->children->pluck('hotel_id') as $hotel) {
            $warningEmails[] = [
                'id_hotel'      => $hotel,
                'warning_email' => $warning_email
            ];
        }

        HotelSatisfaction::insertOnDuplicateKey($warningEmails);
    }

    /**
     * @param PutIncidentsDataType $putData
     */
    public function setIncidentsToSatisfaction(PutIncidentsDataType $putData)
    {
        return $this->incidentRepository->create($putData);
    }

    /**
     * @param PutUserSurveyDataType $putData
     */
    public function updateUserSurvey(PutUserSurveyDataType $putData)
    {
        return $this->userSurveyRepository->update($putData);
    }
}
