<?php

namespace App\Http\Resources\Clients;

use App\Http\Resources\Clients\ClientConnectionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ClientVisitResource extends JsonResource
{

    protected $timezone;

    public function __construct($resource, $timezone)
    {
        parent::__construct($resource);
        $this->timezone = $timezone;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"                => data_get($this, "id"),
            "user_brand_id"     => data_get($this, "user_brand_id"),
            "pms_validated"     => data_get($this, 'pms_id') || data_get($this, 'reservation_id') ? true : false,
            "pms_id"            => data_get($this, 'pms_id'),
            "reservation_id"    => data_get($this, 'reservation_id'),
            "access_code"       => data_get($this, 'access_code'),
            "check_in"          => Carbon::parse(data_get($this, 'check_in'))->format('Y-m-d'),
            "check_out"         => Carbon::parse(data_get($this, 'check_out'))->format('Y-m-d'),
            "channel"           => data_get($this, 'channel'),
            "agency"            => data_get($this, 'agency'),
            "telephone"         => data_get($this, 'telephone'),
            "is_client"         => data_get($this, "is_client"),
            "satisfaction"      => [
                "answer" => data_get($this, "answer"),
                "comment" => data_get($this, "comment"),
                "created_at" => data_get($this, "created_at"),
            ],
            "connections"       => $this->connections->map(function ($connection) {
                return new ClientConnectionResource($connection, $this->timezone);
            }),
        ];
    }
}
