<?php

namespace App\Http\Resources\Loyalty;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Loyalty\OffersResourceCollection;

/**
 * @property mixed hotel
 * @property mixed id
 */
class OffersResource extends JsonResource
{
    protected $lang;

    public function lang($value)
    {
        $this->lang = $value;
        return $this;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request = null)
    {
        $titleOffer = $this->getOfferName($this->offer->offerLang->toArray(), $this->lang);
        if (!$titleOffer) {
            $titleOffer = $this->getOfferName($this->offer->offerLang->toArray(), 'en');
        }

        return [
            "id" => $this->id,
            "visits" => $this->n_triggers,
            "days_to_expire" => $this->days_to_expire,
            "type" => $this->offer_type,
            "brand" => [
                "id" => $this->brand_id,
                "name" => $this->brand->brandInfo->name ? $this->brand->brandInfo->name : $this->brand->brandInfo->nombre
            ],
            "offer" => [
                "id" => $this->offer->id,
                "type" => $this->offer_type,
                "title" => $titleOffer,
                "image" => $this->offer->image_url
            ]

        ];
    }

    private function getOfferName($offers, $lang)
    {
        return data_get(Arr::first($offers, function ($offerLang) use ($lang) {
            return Arr::get($offerLang, 'lang') == $lang;
        }), 'name');
    }

    public static function collection($resource)
    {
        return new OffersResourceCollection($resource);
    }
}
