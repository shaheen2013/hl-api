<?php

namespace App\Http\Resources\Gdpr;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed hotel
 * @property mixed id
 */
class GdprResource extends JsonResource
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
            'restricted_portal' => $this['restrictedPortal'],
            'first_eprivacy_page' => $this['firstEprivacyPage'],
            'second_eprivacy_page' => $this['secondEprivacyPage'],
            'legal_text' => $this['legalText'],
        ];
    }
}
