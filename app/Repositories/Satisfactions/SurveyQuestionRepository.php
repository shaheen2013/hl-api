<?php

namespace App\Repositories\Satisfactions;

use App\SurveyQuestion;

class SurveyQuestionRepository
{
    protected $surveyQuestion;

    public function __construct(SurveyQuestion $surveyQuestion)
    {
        $this->surveyQuestion = $surveyQuestion;
    }

    public function get($surveyID, $questionID)
    {
        return $this->surveyQuestion->where([
            "survey_id" => $surveyID,
            "question_id" => $questionID,
        ])->first();
    }

    public function firstOrCreate($surveyID, $questionID)
    {
        $this->surveyQuestion->firstOrCreate(
            [
                "survey_id" => $surveyID,
                "question_id" => $questionID
            ],
            [
                'active' => 1,
                'required' => 1
            ]
        );
    }

    public function updateOrCreate($surveyID, $questionID, $required, $active)
    {
        $this->surveyQuestion->updateOrCreate(
            [
                "survey_id" => $surveyID,
                "question_id" => $questionID
            ],
            [
                'active' => $active,
                'required' => $required
            ]
        );
    }
}
