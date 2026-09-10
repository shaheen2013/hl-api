<?php

namespace App\Http\Resources\Brands;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $brandInfo = $this->brandInfo;

        return [
            'id'                    => $this->id,
            'name'                  => $brandInfo->name,
            'logo'                  => $brandInfo->logo,
            'background_image'      => $brandInfo->fotoBg,
            'background_color'      => $this->background_color,
            'email'                 => $brandInfo->email,
            'street'                => $brandInfo->street,
            'city'                  => $brandInfo->city,
            'place_name'            => $brandInfo->place_name,
            'place_country'         => $brandInfo->place_country,
            'place_adm_area'        => $brandInfo->place_adm_area,
            'stars'                 => $brandInfo->estrellas,
            'sending_email'         => $brandInfo->sending_email,
            'stay_time'             => $brandInfo->stay_time,
            'time_zone'             => $brandInfo->timeZone->time_zone,
            'products'              => $brandInfo->brandProduct
        ];
    }
}
