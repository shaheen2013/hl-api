<?php

namespace App\Events;

use App\Events\UserEvent;
use Hotelinking\Services\HLConnectionInterface;
use Illuminate\Support\Arr;

class UserUnsubscribed extends UserEvent
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
        $this->userID = Arr::get($payload, 'payload.user.id');
        $this->userService = app('App\Services\Users\UserService');

        $brandService = app('App\Services\BrandService');

        parent::__construct($payload, $connection, $brandService);
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function getBrandID()
    {
        return $this->brandID;
    }

    public function unsubscribe($userID, $brand)
    {
        $this->userService->unsubscribeViaSns($userID, $brand);
    }
}
