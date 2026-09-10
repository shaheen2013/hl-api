<?php

/**
 * Created by PhpStorm.
 * User: jmatesanz
 * Date: 04/11/2019
 * Time: 10:32
 */

namespace App\Repositories\Bookings;

interface BookingFunnelRepositoryInterface
{
    /**
     * @param BookingFunnelTypeInterface $bookingFunnel
     * @return mixed
     */
    public function save(BookingFunnelTypeInterface $bookingFunnel);

    /**
     * @param int $userId
     * @param int $brandId
     * @return int
     */
    public function deleteByUserBrand(int $brandId, int $userId): int;
}
