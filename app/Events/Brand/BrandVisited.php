<?php

namespace App\Events;

use Hotelinking\Services\HLConnectionInterface;
use Illuminate\Support\Arr;

class BrandVisited extends HLEvent
{
    protected $brandID;
    protected $userID;
    protected $brandService;
    protected $userService;

    /**
     * Create a new event instance.
     * @param $payload
     * @param HLConnectionInterface $connection
     */
    public function __construct($payload, HLConnectionInterface $connection)
    {
        $this->userID = Arr::get($payload, 'payload.user.id');
        $this->brandID = Arr::get($payload, 'payload.brand.id');
        $this->brandService = app('App\Services\BrandService');
        $this->userService = app('App\Services\Users\UserService');

        parent::__construct($payload, $connection);
    }

    public function getUser()
    {
        return $this->userService->getUser($this->userID)->load('userGuid');
    }

    public function getBrand()
    {
        return $this->brandService->getBrand($this->brandID)->load('brandProtocol');
    }
}
