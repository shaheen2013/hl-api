<?php

namespace App\Http\Resources\Clients;

use App\Http\Resources\Clients\ClientVisitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientDetailResource extends JsonResource
{
    private $propertyVisits;
    private $chainVisits;
    private $timezone;

    public function __construct($propertyVisits, $chainVisits, $timezone, $resource)
    {
        $this->propertyVisits = $propertyVisits;
        $this->chainVisits = $chainVisits;
        $this->timezone = $timezone;

        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->map(function ($client) {
            return [
                'id'                => data_get($client, 'user_id'),
                'user_brand_id'     => data_get($client, 'id'),
                'name'              => data_get($client, 'user_name'),
                'email'             => data_get($client, 'user_email'),
                'country'           => data_get($client, 'country'),
                'location'          => data_get($client, 'locationName'),
                'birthday'          => data_get($client, 'fecha_nacimiento'),
                'gender'            => data_get($client, 'sexo'),
                'card'              => data_get($client, 'user_card'),
                'unsubscribed'      => data_get($client, 'unsubscribed'),
                'image'             => data_get($client, 'facebook_img'),
                'link'              => data_get($client, 'link'),
                'friends'           => data_get($client, 'amigos'),
                'brand_id'          => data_get($client, 'brand_id'),
                'brand_name'        => data_get($client, 'hotelName'),
                'phone_number'      => data_get($client, 'phone_number'),
                'property_visits'   => $this->propertyVisits,
                'chain_visits'      => $this->chainVisits,
                'visits'            => $client->visits->map(function ($visit) {
                    return new ClientVisitResource($visit, $this->timezone);
                }),
            ];
        });
    }
}
