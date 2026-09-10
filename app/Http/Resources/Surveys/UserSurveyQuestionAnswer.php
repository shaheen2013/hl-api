<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSurveyQuestionAnswer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "survey_question_id" => $this->survey_question_id,
            "user_survey_id" => $this->user_survey_id,
            "user_id" => $this->user_id,
            "score" => $this->answer,
            "name" => $this->name,
            "comment" => $this->comment,
            "lang" => $this->lang,
            "favorite" => $this->favorite,
            "created_at" => $this->created_at->toDateTimeString(),
            "updated_at" => $this->updated_at->toDateTimeString(),
        ];
    }
}
