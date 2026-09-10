<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 26/03/2019
 * Time: 12:53
 */

namespace App\Http\Resources\Stats;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class SurveyCategoryStat extends JsonResource
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
        if (isset($this->question->category->categoryText)) {
            foreach ($this->question->category->categoryText as $surveyText) {
                $surveyTexts[$surveyText->lang_value] = $surveyText->text;
            }
        }

        return [
            'survey_category_id' => $this->survey_category_id,
            'average' => $this->average,
            'numAnswers' => $this->numAnswers,
            'category_texts' => $surveyTexts,
        ];
    }
}
