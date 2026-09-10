<?php

namespace App\Events;

use App\Events\HLEvent;
use Hotelinking\Services\HLConnectionInterface;
use App\Services\BrandService;
use App\Services\Users\UserService;

class UserEvent extends HLEvent
{
    /**
     * Create a new event instance.
     *
     * @param $payload
     * @param $connection
     */
    public function __construct($payload, HLConnectionInterface $connection, BrandService $brandService)
    {
        $this->brandService = app('App\Services\BrandService');
        parent::__construct($payload, $connection);
        $this->brandService = $brandService;
    }

    public function getBrand()
    {
        return $this->brandService->getBrand($this->brandID);
    }
}
