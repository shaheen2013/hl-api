<?php

namespace App\Http\Resources\Reward;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * @property mixed hotel
 * @property mixed id
 */
class RewardResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $offer = $this->getOfferLang($this->offerLang, $request->lang);

        if (empty($offer)) {
            $offer = $this->getOfferLang($this->offerLang, 'en');
        }

        return [
            'id' => $this->id,
            'booking_engine_code' => $this->booking_engine_code,
            'start' => $this->start,
            'end' => $this->end,
            'image_url' => $this->iamge_url,
            'offer_type' =>  $this->offer_type,
            'category' => $this->category,
            "offer_lang" => $offer

        ];
    }

    private function getOfferLang($offers, $lang)
    {
        return Arr::first($offers->toArray(), function ($value, $key) use ($lang) {
            return $value['lang'] == $lang;
        });
    }
}
