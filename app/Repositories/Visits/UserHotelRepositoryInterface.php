<?php

namespace App\Repositories\Visits;

/**
 * Interface UserHotelRepositoryInterface
 * @package App\Repositories\Visits
 */
interface UserHotelRepositoryInterface
{
    /**
     * @param $userID
     * @param $hotelID
     * @return mixed
     */
    public function get($userID, $hotelID);

    /**
     * @param $userID
     * @param $hotelID
     * @return mixed
     */
    public function unsubscribe($userID, $hotelID);

    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId);

    /**
     * @param $userID
     * @param $brandID
     * @return mixed
     */
    public function getByUserIDBrandID(int $userID, int $brandID);
}
