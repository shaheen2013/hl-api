<?php

namespace App\Events;

use App\Events\UserEvent;
use Hotelinking\Services\HLConnectionInterface;
use Illuminate\Support\Arr;

class GdprAccepted extends UserEvent
{
    protected $brandID;
    private $userID;

    /**
     * Create a new event instance.
     * @param $payload
     * @param HLConnectionInterface $connection
     */
    public function __construct($payload, HLConnectionInterface $connection)
    {
        $this->brandID = Arr::get($payload, 'context.brandID');
        $this->userID = Arr::get($payload, 'payload.userID');

        $brandService = app('App\Services\BrandService');

        parent::__construct($payload, $connection, $brandService);
    }

    public function getUserID()
    {
        return $this->userID;
    }
}
