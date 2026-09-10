<?php

namespace App\Repositories\Satisfactions;

use App\UserSurvey;
use App\Types\Survey\PutUserSurveyDataType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class UserSurveyRepository
{
    protected $userSurvey;

    public function __construct(UserSurvey $userSurvey)
    {
        $this->userSurvey = $userSurvey;
    }

    public function getByUserSatisfaction($satisfactionID)
    {
        return $this->userSurvey->where([
            "user_satisfaction_id" => $satisfactionID
        ])->first();
    }

    public function update(PutUserSurveyDataType $putData)
    {
        return $this->userSurvey->where(["id" => $putData->userSurveyId])->update(["incidents_reviewed" => $putData->incidentsReviewed]);
    }

    public function setReviewSent($userSurveyID)
    {
        $this->userSurvey->where('id', $userSurveyID)->update(['review_sent' => 1]);
    }

    public function updateNotAnsweredUserSurvey($brandId, $surveyId)
    {
        $this->userSurvey
            ->leftJoin('user_survey_question_answer', 'user_survey_question_answer.user_survey_id', '=', 'user_survey.id')
            ->whereNull('user_survey_question_answer.id')
            ->where(["brand_id" => $brandId])
            ->toBase()
            ->update([
                "survey_id"                 => $surveyId,
                "user_survey.updated_at"    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
    }

    public function getUserSatisfactionId($userSurveyId)
    {
        return $this->userSurvey->where('id', $userSurveyId)->value('user_satisfaction_id');
    }
}
