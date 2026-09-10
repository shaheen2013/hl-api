<?php

namespace App\Http\Resources\Clients;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ClientConnectionResource extends JsonResource
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
            "id"                        => data_get($this, "id"),
            "access_code"               => data_get($this, "access_code"),
            "mac_address"               => data_get($this, "mac_address"),
            "device_family"             => data_get($this, "device_family"),
            "operating_system"          => data_get($this, "operating_system"),
            "operating_system_version"  => data_get($this, "operating_system_version"),
            "source"                    => data_get($this, "name"),
            "created_at"                => Carbon::createFromFormat('Y-m-d H:i:s', data_get($this, "created_at"), 'UTC')->setTimezone($this->timezone)->toDateTimeString()
        ];
    }
}
