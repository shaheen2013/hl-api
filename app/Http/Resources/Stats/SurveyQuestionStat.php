<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 26/03/2019
 * Time: 12:53
 */

namespace App\Http\Resources\Stats;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyQuestionStat extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $surveyTexts = [];
        if (isset($this->question->questionText)) {
            foreach ($this->question->questionText as $surveyText) {
                $surveyTexts[$surveyText->lang_value] = $surveyText->text;
            }
        }

        $surveyCatTexts = [];
        if (isset($this->question->category->categoryText)) {
            foreach ($this->question->category->categoryText as $surveyText) {
                $surveyCatTexts[$surveyText->lang_value] = $surveyText->text;
            }
        }

        return [
            'survey_question_id' => $this->survey_question_id,
            'average' => $this->average,
            'numAnswers' => $this->numAnswers,
            'question_texts' => $surveyTexts,
            'category_texts' => $surveyCatTexts
        ];
    }
}
