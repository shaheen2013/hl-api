<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandUserByEmailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'brand_id' => $this->brand_id,
            'date' => $this->date,
            'unsubscribed' => $this->unsubscribed,
            'email' => $this->email,
            'name' => $this->name,
            'hotel_id' => $this->hotel_id
        ];
    }
}
