<?php

namespace App\Repositories\Satisfactions;

use App\SatisfactionAnswer;
use App\Types\Survey\SaveSurveyAnswersDataType;
use Illuminate\Support\Facades\Log;

class CustomizedSatisfactionAnswerRepository
{
    protected $satisfactionAnswer;

    public function __construct(SatisfactionAnswer $satisfactionAnswer)
    {
        $this->satisfactionAnswer = $satisfactionAnswer;
    }

    public function firstOrCreate(SaveSurveyAnswersDataType $surveyAnswersData)
    {
        $responseIds = $surveyAnswersData->responseIds ? $surveyAnswersData->responseIds : [null];

        foreach ($responseIds as $responseId) {
            $this->satisfactionAnswer->firstOrCreate(
                [
                    'user_satisfaction_id'  => $surveyAnswersData->surveyId,
                    'survey_question_id'    => $surveyAnswersData->questionId,
                    'question_response_id'  => $responseId
                ],
                [
                    'answer'                => $surveyAnswersData->score,
                    'brand_id'              => $surveyAnswersData->brandId,
                    'comment'               => $surveyAnswersData->comment,
                ]
            );
        }
    }

    public function deleteById($userSatisfactionId)
    {
        try {
            $this->satisfactionAnswer->where('user_satisfaction_id', $userSatisfactionId)->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting customized satisfaction answer: ' . $e->getMessage());
        }
    }
}
