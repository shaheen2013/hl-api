<?php

namespace Tests\Feature;

use App\Hotel;
use App\User;
use App\Brand;
use App\BookingFunnel;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    public function testBookingFunnelCreateSuccessfully()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $user = factory(User::class)->create();
        $referrer = factory(User::class)->create();

        $this->json('POST', "/api/brands/$brand->id/bookings/funnel", [
            'brand_id'  => $brand->id,
            'user_id'   => $user->id,
            'referrer_id' => $referrer->id,
            'session' => 'sessionTest',
            'booking_action' => 'home',
            'source' => 'facebook',
            'source_action' => 'share',
        ])->assertStatus(204);

        $bookingFunnel = BookingFunnel::first();
        $this->assertNotEquals($bookingFunnel, null);
    }

    public function testMissingDataErrorIfNotBookingActionSet()
    {
        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $user = factory(User::class)->create();
        $referrer = factory(User::class)->create();

        $this->json('POST', "/api/brands/$brand->id/bookings/funnel", [
            'brand_id'  => $brand->id,
            'user_id'   => $user->id,
            'referrer_id' => $referrer->id,
            'session' => 'sessionTest',
            'source' => 'facebook',
            'source_action' => 'share',
        ])->assertStatus(422);
    }
}
