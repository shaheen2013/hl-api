<?php

namespace App\Services\Users;

use App\Repositories\Visits\UserHotelRepositoryInterface;

class UserHotelService
{
    protected $userHotel;

    public function __construct(UserHotelRepositoryInterface $userHotel)
    {
        $this->userHotel = $userHotel;
    }

    public function deleteUserHotelByUserId(int $userId)
    {
        return $this->userHotel->deleteByUserId($userId);
    }
}
