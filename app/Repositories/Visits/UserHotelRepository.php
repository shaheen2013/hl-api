<?php

namespace App\Repositories\Visits;

use App\UserHotel;
use Illuminate\Support\Facades\Cache;

class UserHotelRepository implements UserHotelRepositoryInterface
{
    /**
     * @var UserHotel
     */
    protected $userHotel;

    /**
     * UserHotelRepository constructor.
     * @param UserHotel $userHotel
     */
    public function __construct(UserHotel $userHotel)
    {
        $this->userHotel = $userHotel;
    }
    /**
     * @param $userID
     * @param $hotelID
     * @return mixed
     */
    public function get($userID, $hotelID)
    {
        return $this->userHotel->where([
            "id_usuario" => $userID,
            "id_hotel" => $hotelID
        ])->firstOrFail();
    }

    /**
     * @param $userID
     * @param $BrandID
     * @return mixed
     */
    public function getByUserIDBrandID($userID, $brandID)
    {
        return $this->userHotel
            ->join('brands', 'brands.hotel_id', 'user_hotels.id_hotel')
            ->where([
                "id_usuario" => $userID,
                'brands.id' => $brandID
            ])->first();
    }

    /**
     * @param $userID
     * @param $hotelID
     */
    public function unsubscribe($userID, $hotelID)
    {
        $this->userHotel->where([
            'id_usuario' => $userID,
            'id_hotel' => $hotelID
        ])->update(['unsubscribed' => 1]);

        Cache::tags(["user-{$userID}"])->flush();
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function deleteByUserId(int $userId)
    {
        return $this->userHotel->where('id_usuario', $userId)->delete();
    }
}
