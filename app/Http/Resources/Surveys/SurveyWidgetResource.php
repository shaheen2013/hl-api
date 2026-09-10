<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed hotel
 * @property mixed id
 */
class SurveyWidgetResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'rate' => $this->answer,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'user' => [
                "name" => $this->userSurvey->user->name,
            ],
            'brand' => [
                "brand_type" => $this->userSurvey->survey->brand->hotel_id ? 'hotel' : 'chain'
            ],
            'hotel' => [
                "name" => $this->userSurvey->survey->brand->hotel_id ? $this->userSurvey->survey->brand->hotel->name : $this->userSurvey->userSatisfaction->hotel->name,
            ],
            'user_survey' => [
                "id" => $this->userSurvey->id,
            ]
        ];
    }
}
