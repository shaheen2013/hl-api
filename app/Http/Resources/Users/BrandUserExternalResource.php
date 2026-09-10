<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class BrandUserExternalResource extends JsonResource
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
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            'birthday' => $this->birthday,
            'phone_number' => $this->phone_number,
            'gender' => $this->gender,
            "document_id" => $this->document_id,
            'locale' => $this->location,
            'lang' => $this->lang,
            'country' => $this->country,
            'email_result' => $this->email_result,
            'sendex' => $this->sendex,
            "unsubscribed" => $this->unsubscribed, // TODO: After clients has been notified, we can remove this one
            "gdpr_accepted" => !$this->unsubscribed,
            "facebook" => [
                'id' => $this->facebook_id,
                'facebook_img' => $this->facebook_img,
                'location_id' => $this->location_id,
                'location_name' => $this->location_name,
            ],
            'brand' => [
                'id' => $this->brand_id,
                'name' => $this->hotel_name,
                'last_visit' => $this->last_visit
            ],
            "created_at" => $this->created_at,
            "visit" => [
                "room_number" => $this->getLastRoomNumber($this->connections->toArray()),
            ],
            'reservation' => json_decode($this->reservation)
        ];
    }

    private function getLastRoomNumber($connections)
    {
        usort($connections, function ($a, $b) {
            return strtotime(data_get($a, 'created_at')) - strtotime(data_get($b, 'created_at'));
        });
        
        return data_get(
            Arr::first(array_reverse($connections), function ($key, $value) {
                return data_get($value, 'access_code') != "Bypass";
            }),
            'access_code'
        );
    }
}
