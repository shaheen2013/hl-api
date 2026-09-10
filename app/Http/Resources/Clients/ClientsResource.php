<?php

namespace App\Http\Resources\Clients;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ClientsResource extends JsonResource
{
    private $timezone;
    private $pagination;

    public function __construct($brand, $resource, $total, $page, $perPage)
    {
        $this->pagination = [
            'total' => $total,
            'count' => $perPage,
            'perPage' => $perPage,
            'currentPage' => $page,
            'totalPages' => ceil($total / $perPage)
        ];

        $this->timezone = $brand->hotel ?
            $brand->hotel->timeZone->time_zone :
            $brand->children[0]->hotel->timeZone->time_zone;

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
        $resource = $this->resource->map(function ($client) {
            $connections = collect(json_decode(data_get($client, 'connections'), true));
            $lastConnection = $connections->last(function ($value) {
                return $value;
            });

            return [
                'user_id'       => data_get($client, 'user_id'),
                'client_name'   => data_get($client, 'client_name'),
                'client_email'  => data_get($client, 'client_email'),
                'brand_name'    => data_get($client, 'hotelName'),
                'first_login'   => Carbon::createFromFormat('Y-m-d H:i:s', data_get($client, 'date'), 'UTC')->setTimezone($this->timezone)->toDateTimeString(),
                'check_in'      => data_get($client, 'check_in'),
                'check_out'     => data_get($client, 'check_out'),
                'channel'       => data_get($client, 'channel'),
                'agency'       => data_get($client, 'agency'),
                'unsubscribed'  => data_get($client, 'unsub'),
                'phone_number'  => data_get($client, 'phone_number') ?? null,
                'room_number'   => data_get($lastConnection, 'access_code'),
            ];
        });

        return [
            "meta" => $this->pagination,
            "data" => $resource
        ];
    }
}
