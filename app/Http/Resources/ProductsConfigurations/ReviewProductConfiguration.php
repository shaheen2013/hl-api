<?php

namespace App\Http\Resources\ProductsConfigurations;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed hotel
 * @property mixed id
 */
class ReviewProductConfiguration extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $hotel = $this->hotel;

        if ($hotel) {
            $configuration = data_get($hotel, 'hotelReview');

            return [
                'brand_id' => $this->id,
                'send_after_days' => data_get($configuration, 'diasEnvio', 0),
                'ignore_rating' => data_get($configuration, 'ignoreRating', 0),
                'send_type' => data_get($configuration, 'send_type'),
                'active' => $configuration ? 1 : 0
            ];
        }
    }
}
