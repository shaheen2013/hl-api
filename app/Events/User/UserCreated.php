<?php

namespace App\Events;

use App\Events\UserEvent;
use App\Services\BrandService;
use App\Services\Users\UserBrandService;
use App\Services\Users\UserService;
use Hotelinking\Services\HLConnectionInterface;
use Illuminate\Support\Arr;

class UserCreated extends UserEvent
{
    protected $brandID;
    private $userID;
    private $birthdate;
    protected $userService;
    protected $userBrandService;

    /**
     * Create a new event instance.
     * @param $payload
     * @param HLConnectionInterface $connection
     */
    public function __construct($payload, HLConnectionInterface $connection)
    {
        $this->brandID = Arr::get($payload, 'payload.brand.id', Arr::get($payload, 'context.brandID'));
        $this->userID = Arr::get($payload, 'payload.user.id', Arr::get($payload, 'payload.id'));
        $this->birthdate = Arr::get($payload, 'payload.user.birthDate', Arr::get($payload, 'payload.birthDate'));

        $this->userService = app(UserService::class);
        $brandService = app(BrandService::class);
        $this->userBrandService = app(UserBrandService::class);

        parent::__construct($payload, $connection, $brandService);
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function getGeneration($birthdate)
    {
        return $this->userService->getGeneration($birthdate);
    }

    public function update($userID, $generation)
    {
        $this->userService->updateGeneration($userID, $generation);
        $this->userBrandService->updateGeneration($this->brandID, $userID, $generation);
    }
}
