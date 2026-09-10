<?php

namespace App\Repositories\Bookings;

use App\BookingFunnel;

class BookingFunnelRepository implements BookingFunnelRepositoryInterface
{
    /**
     * @var BookingFunnel
     */
    protected $bookingFunnel;

    /**
     * BookingFunnelRepository constructor.
     * @param BookingFunnel $bookingFunnel
     */
    public function __construct(BookingFunnel $bookingFunnel)
    {
        $this->bookingFunnel = $bookingFunnel;
    }

    /**
     * @param BookingFunnelTypeInterface $bookingFunnel
     * @return mixed|void
     */
    public function save(BookingFunnelTypeInterface $bookingFunnel)
    {
        $bookingFunnel = $bookingFunnel->get();

        $this->bookingFunnel->create([
            'brand_id' => data_get($bookingFunnel, 'brandID'),
            'user_id' => data_get($bookingFunnel, 'userID'),
            'referrer_id' => data_get($bookingFunnel, 'referrerID'),
            'session' => data_get($bookingFunnel, 'session'),
            'date' => data_get($bookingFunnel, 'date'),
            'booking_action' => data_get($bookingFunnel, 'bookingAction'),
            'source' => data_get($bookingFunnel, 'source'),
            'source_action' => data_get($bookingFunnel, 'sourceAction')
        ]);
    }

    /**
     * @param int $userId
     * @param int $brandId
     * @return int
     */
    public function deleteByUserBrand(int $brandId, int $userId): int
    {
        return $this->bookingFunnel
            ->where('user_id', $userId)
            ->where('brand_id', $brandId)
            ->delete();
    }
}
