<?php

namespace App\Services\Bookings;

use App\Services\Users\UserService;
use App\Repositories\Bookings\BookingFunnelTypeInterface;
use App\Repositories\Bookings\BookingFunnelRepositoryInterface;
use Illuminate\Support\Facades\Log;

class BookingsService
{
    protected $bookingFunnelRepository;
    protected $userService;

    public function __construct(BookingFunnelRepositoryInterface $bookingFunnelRepository, UserService $userService)
    {
        $this->bookingFunnelRepository = $bookingFunnelRepository;
        $this->userService = $userService;
    }


    public function saveBookingFunnel(BookingFunnelTypeInterface $bookingFunnel)
    {
        $bookingFunnelData = $bookingFunnel->get();

        try {
            if (data_get($bookingFunnelData, 'userID')) {
                $this->userService->getUser(data_get($bookingFunnelData, 'userID'));
            } else {
                $bookingFunnel->set('userID', null);
            }
        } catch (\Exception $e) {
            $bookingFunnel->set('userID', null);
            Log::warning("UserId not valid on booking funnel", [data_get($bookingFunnelData, 'userID')]);
        }

        try {
            if (data_get($bookingFunnelData, 'referrerID')) {
                $this->userService->getUser(data_get($bookingFunnelData, 'referrerID'));
            } else {
                $bookingFunnel->set('referrerID', null);
            }
        } catch (\Exception $e) {
            $bookingFunnel->set('referrerID', null);
            Log::warning("ReferrerId not valid on booking funnel", [data_get($bookingFunnelData, 'referrerID')]);
        }

        $this->bookingFunnelRepository->save($bookingFunnel);
    }

    /**
     * @param int $userId
     * @param int $brandId
     * @return int
     */
    public function deleteByUserBrand(int $userId, int $brandId): int
    {
        return $this->bookingFunnelRepository
            ->deleteByUserBrand($userId, $brandId);
    }
}
