<?php

namespace App\Http\Resources\Stats;

use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyVisitorInfo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->user_id,
            'name'       => $this->nombre,
            'age'        => $this->age,
            'country'    => $this->pais,
            'location'   => $this->location,
            'language'   => $this->lang,
            'hotel_name' => $this->hotelName,
            'room'       => $this->id_room
        ];
    }
}
