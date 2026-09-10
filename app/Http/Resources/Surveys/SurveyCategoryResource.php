<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed hotel
 * @property mixed id
 */
class SurveyCategoryResource extends JsonResource
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
            'brand_id' => $this->brand_id,
            'survey_category_text' => $this->categoryText
        ];
    }
}
