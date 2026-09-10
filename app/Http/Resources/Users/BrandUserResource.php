<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandUserResource extends JsonResource
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
            'id' => $this->user_id,
            'email' => $this->email,
            'name' => $this->name,
            // "first_name" => $this->first_name,
            // "last_name" => $this->last_name,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            // "document_id" => $this->document_id,
            'location' => $this->location,
            'lang' => $this->lang,
            'country' => $this->country,
            'email_result' => $this->email_result,
            'sendex' => $this->sendex,
            // "unsubscribed" => $this->unsubscribed,
            // "facebook" => [
            //     'id' => $this->facebook_id,
            //     'facebook_img' => $this->facebook_img,
            //     'location_id' => $this->location_id,
            //     'location_name' => $this->location_name,
            // ],
            'brand' => [
                'id' => $this->brand_id,
                'name' => $this->hotel_name,
                'num_visits' => $this->num_visits,
                'last_visit' => $this->last_visit
            ],
            'reservation' => json_decode($this->reservation)
            // "created_at" => $this->created_at,
        ];
    }
}
