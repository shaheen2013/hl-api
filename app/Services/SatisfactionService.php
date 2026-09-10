<?php

namespace App\Services;

use App\Repositories\Satisfactions\SatisfactionRepository;
use App\Repositories\Satisfactions\UserSurveyQuestionAnswerRepository;
use App\Repositories\Satisfactions\UserSurveyRepository;
use App\Repositories\Satisfactions\SurveyQuestionRepository;
use App\Repositories\Satisfactions\QuestionBrandRepository;
use App\Repositories\Satisfactions\SurveyRepository;
use App\Services\BrandService;
use App\Services\Connections\ConnectionHistoryService;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\BrandIdsListNotSameChainException;
use App\Repositories\Satisfactions\CustomizedSatisfactionAnswerRepository;
use App\Repositories\Users\UserSatisfactionRepository;
use App\Types\Survey\SurveyAnswersDataType;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SatisfactionService
{
    protected $surveyRepository;
    protected $satisfactionRepository;
    protected $userSurveyQuestionAnswerRepository;
    protected $userSurveyRepository;
    protected $surveyQuestionRepository;
    protected $questionBrandRepository;
    protected $brandService;
    protected $userSatisfactionRepository;
    protected $customizedSatisfactionAnswerRepository;

    public function __construct(
        SurveyRepository $surveyRepository,
        SatisfactionRepository $satisfactionRepository,
        UserSurveyQuestionAnswerRepository $userSurveyQuestionAnswerRepository,
        UserSurveyRepository $userSurveyRepository,
        SurveyQuestionRepository $surveyQuestionRepository,
        QuestionBrandRepository $questionBrandRepository,
        BrandService $brandService,
        UserSatisfactionRepository $userSatisfactionRepository,
        CustomizedSatisfactionAnswerRepository $customizedSatisfactionAnswerRepository
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->satisfactionRepository = $satisfactionRepository;
        $this->userSurveyQuestionAnswerRepository = $userSurveyQuestionAnswerRepository;
        $this->userSurveyRepository = $userSurveyRepository;
        $this->surveyQuestionRepository = $surveyQuestionRepository;
        $this->questionBrandRepository = $questionBrandRepository;
        $this->brandService = $brandService;
        $this->userSatisfactionRepository = $userSatisfactionRepository;
        $this->customizedSatisfactionAnswerRepository = $customizedSatisfactionAnswerRepository;
    }

    /**
     * @param $lang
     * @param $brand_id
     * @param $days
     * @param $exclusiveFavorite
     * @param string $brand_type
     * @param int $score
     * @param int $limit
     * @return Satisfaction[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getSatisfactionByLang($lang, $brand_id, $days, $exclusiveFavorite, $score = 0, $limit = 10)
    {
        // Changed from SatisfactionRepository to UserSurveyQuestionAnswerRepository. Both working
        return $this->userSurveyQuestionAnswerRepository->getSatisfactionByLang($lang, $brand_id, $days, $exclusiveFavorite, $score, $limit);
    }

    public function createSurvey($brandID, $name)
    {
        $survey = $this->surveyRepository->firstOrCreate($brandID, $name);

        $defaultQuestion = $this->questionBrandRepository->getDefaultQuestion();
        $this->surveyQuestionRepository->firstOrCreate($survey->id, $defaultQuestion->question_id);

        return $survey;
    }

    public function getSurvey($brandID)
    {
        return $this->surveyRepository->get($brandID);
    }

    public function updateOrCreateSurveyQuestion($surveyID, $questionID, $required, $active)
    {
        $this->surveyQuestionRepository->updateOrCreate($surveyID, $questionID, $required, $active);
    }

    public function saveUserSatisfaction($satisfactionID, $score, $comment)
    {
        $this->satisfactionRepository->save($satisfactionID, $score, $comment);
    }

    public function saveUserSurveyQuestionAnswer($userSurveyID, $questionID, $score, $comment, $responseId = null)
    {
        $this->userSurveyQuestionAnswerRepository->save($userSurveyID, $questionID, $score, $comment, $responseId);
    }

    public function setSurveyReviewSent($satisfactionID, $userSurvey)
    {
        $this->satisfactionRepository->setReviewSent($satisfactionID);

        if ($userSurvey) {
            $this->userSurveyRepository->setReviewSent($userSurvey->id);
        }
    }

    public function getUserSurvey($satisfactionID)
    {
        return $this->userSurveyRepository->getByUserSatisfaction($satisfactionID);
    }

    public function updateNotAnsweredUserSurvey($brandId, $surveyId)
    {
        return $this->userSurveyRepository->updateNotAnsweredUserSurvey($brandId, $surveyId);
    }

    public function getDefaultSurveyQuestion($surveyID)
    {
        $defaultQuestion = $this->questionBrandRepository->getDefaultQuestion();

        return $this->getSurveyQuestion($surveyID, $defaultQuestion->question_id);
    }

    public function getSurveyQuestion($surveyID, $questionID)
    {
        return $this->surveyQuestionRepository->get($surveyID, $questionID);
    }

    /**
     * Method to get surveys answers filtering by brand_id and applying some filter like favorite, dates, ...
     *
     * @param int brand_id
     * @param App\Types\Survey\SurveyAnswersDataType surveyAnswersDataType
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getSurveysAnswers(int $brandId, SurveyAnswersDataType $surveyAnswersDataType): LengthAwarePaginator
    {
        // Get brandIds to perform a check if is a chain or a hotel
        $brand = $this->brandService->getBrand($brandId);

        // Check if is a chain
        if ($brand->chain_id) {
            // In case of chain, we need to verify if all hotels on the list of brands belongs to this chain
            $parent = $brand;
            // Get list of brand from inputs
            $brandIds = collect($surveyAnswersDataType->brands);
            // Get list of childrens to validate
            $brandIdsToCheck = $parent->children->pluck('id')->values();

            if ($brandIds->isEmpty()) {
                // In case not passed a list of brands for a chain, search for all hotels for this chain
                $brandIds = $parent->children->pluck('id')->values();
            } else {
                // Check if that is a brand that no belongs to this chain
                if ($brandIds->diff($brandIdsToCheck)->isNotEmpty()) {
                    throw new BrandIdsListNotSameChainException();
                }
            }
        } else {
            // In case of hotel get the parent
            $parent = $brand->parent ?? $brand;
            // Do not look for brands on inputs
            $brandIds = collect([$brandId]);
        }

        return $this->userSurveyQuestionAnswerRepository
            ->getPaginatedAnswerWithFilters($parent->id, $brandIds->toArray(), $surveyAnswersDataType);
    }

    public function getSurveyReport($brandId, $data, $headers)
    {
        $customizedSurveyService = app('App\Services\Products\CustomizedSurveyProductService');

        $customizedQuestions = $customizedSurveyService->getQuestions($brandId, "en");
        return $this->surveyRepository->getCsv($data, $customizedQuestions, $headers);
    }

    public function deleteUserSurvey($brandId, $userSurveyId)
    {
        $userSatisfactionId = $this->userSurveyRepository->getUserSatisfactionId($userSurveyId);
        
        $this->userSurveyQuestionAnswerRepository->deleteUserSurveyQuestionAnswer($userSurveyId);
        $this->userSatisfactionRepository->deleteById($userSatisfactionId);
        $this->customizedSatisfactionAnswerRepository->deleteById($userSatisfactionId);
    }
}
