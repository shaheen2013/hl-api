<?php

namespace App\Http\Resources\OfferWifi;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferWifiResource extends JsonResource
{
    const DEFAULT_OFFER_LANG = 'en';
    private $lang;

    public function __construct($resource, $lang)
    {
        $this->lang = $lang;
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        if ($this->lang) {
            $resource = $this->resource->map(function ($offerWifi) {
                $description = $offerWifi->description->get($this->lang);
                if (!$description) {
                    $description = $offerWifi->description->get(self::DEFAULT_OFFER_LANG);
                }

                return [
                    'id'                => $offerWifi->id,
                    'accommodated'      => $offerWifi->accommodated,
                    'non_accommodated'  => $offerWifi->non_accommodated,
                    'offer_id'          => $offerWifi->offer_id,
                    'condition'         => $offerWifi->condition,
                    'offer_type'        => $offerWifi->offer_type,
                    'period'            => $offerWifi->period,
                    'valid_from'        => $offerWifi->valid_from,
                    'valid_to'          => $offerWifi->valid_to,
                    'is_default'        => $offerWifi->is_default,
                    'img'               => $offerWifi->img,
                    'description'       => $description
                ];
            });

            return $resource;
        }

        return $this->resource;
    }
}
