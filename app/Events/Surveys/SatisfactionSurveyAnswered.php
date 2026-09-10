<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 04/04/2019
 * Time: 09:06
 */

namespace App\Events;

use App\Satisfaction;
use Hotelinking\Services\HLConnectionInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class SatisfactionSurveyAnswered extends HLEvent
{
    /**
     * Create a new event instance.
     *
     * @param $payload
     * @param $connection
     */
    public function __construct($payload, HLConnectionInterface $connection)
    {
        $rawComment = Arr::get($payload, 'payload.satisfaction.comment');
        $comment = json_decode($rawComment, true) ?? $rawComment;

        $this->user_id = Arr::get($payload, 'payload.user.id');
        $this->brand_id = Arr::get($payload, 'payload.brand.id');
        $this->satisfaction_id = Arr::get($payload, 'payload.satisfaction.id');
        $this->score = Arr::get($payload, 'payload.satisfaction.score');
        $this->comment = is_string($comment) ? $comment : $rawComment;
        $this->brandService = app('App\Services\BrandService');
        $this->surveyProductService = app('App\Services\Products\SatisfactionSurveyProductService');
        $this->surveyService = app('App\Services\SatisfactionService');
        $this->userService = app('App\Services\Users\UserService');
        $this->reviewConfigurationService = app('App\Services\Products\ReviewProductService');

        parent::__construct($payload, $connection);
    }

    public function getSurveysAnswer()
    {
            $surveyAnswer = [
                "score" => $this->score,
                "comment" => $this->comment
            ];
            return (object) $surveyAnswer;
    }

    public function getBrandSatisfactionConfiguration()
    {
        return (object) $this->surveyProductService->getConfiguration($this->brand_id)->toArray(null);
    }

    public function updateSatisfactionSurvey($satisfactionID, $score, $comment)
    {
        $this->surveyService->saveUserSatisfaction($satisfactionID, $score, $comment);

        $userSurvey = $this->surveyService->getUserSurvey($satisfactionID);

        if ($userSurvey) {
            $question = $this->surveyService->getDefaultSurveyQuestion($userSurvey->survey_id);

            if ($question) {
                $this->surveyService->saveUserSurveyQuestionAnswer($userSurvey->id, $question->id, $score, $comment);
                // Clear survey cache for this brand
                Cache::tags("surveys-$userSurvey->brand_id")->flush();
            }
        }
    }

    public function setReviewSent($satisfactionID)
    {
        $userSurvey = $this->surveyService->getUserSurvey($satisfactionID);
        $this->surveyService->setSurveyReviewSent($satisfactionID, $userSurvey);
    }

    public function getBrandReviewConfiguration()
    {
        return (object) $this->reviewConfigurationService->getConfiguration($this->brand_id)->toArray(null);
    }

    public function getUser()
    {
        return $this->userService->getUser($this->user_id);
    }

    public function getBrand()
    {
        return $this->brandService->getBrand($this->brand_id);
    }

    public function getSurvey()
    {
        return $this->surveyProductService->getSurvey($this->brand_id, $this->satisfaction_id);
    }
}
