<?php

namespace Tests\Feature;

use App\User;
use App\Brand;
use App\Hotel;
use App\UserBrand;
use App\UserHotel;
use Carbon\Carbon;
use App\Jobs\ProcessBulkUnsubscribe;
use App\Types\Users\BulkUnsubscribeDataType;
use Tests\TestCase;

class WorkerBulkUnsubscribeTest extends TestCase
{
    public function testProcessBulkUnsubscribeSuccessfully()
    {
        $hotel = factory(Hotel::class)->create();
        $hotel2 = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $brand2 = Brand::where('hotel_id', '=', $hotel2->id)->first();

        $suscribedUser = factory(User::class)->create();
        $suscribedUserOtherBrand = factory(User::class)->create();
        $suscribedUserBothBrand = factory(User::class)->create();
        $unsubscribedUser = factory(User::class)->create();

        $suscribedUserBrand = factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $suscribedUser->id,
            'unsubscribed'  => 0
        ]);
        $suscribedUserHotel = factory(UserHotel::class)->create([
            'id_usuario' => $suscribedUser->id,
            'id_hotel' =>  $hotel->id,
            'unsubscribed' => 0
        ]);

        $suscribedUserOtherUserBrand = factory(UserBrand::class)->create([
            'brand_id'      => $brand2->id,
            'user_id'       => $suscribedUserOtherBrand->id,
            'unsubscribed'  => 0
        ]);
        $suscribedUserOtherUserHotel = factory(UserHotel::class)->create([
            'id_usuario' => $suscribedUserOtherBrand->id,
            'id_hotel' =>  $hotel2->id,
            'unsubscribed' => 0
        ]);

        $suscribedBothUserBrand = factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $suscribedUserBothBrand->id,
            'unsubscribed'  => 0
        ]);
        $suscribedBothUserHotel = factory(UserHotel::class)->create([
            'id_usuario' => $suscribedUserBothBrand->id,
            'id_hotel' =>  $hotel->id,
            'unsubscribed' => 0
        ]);
        $suscribedBothUserBrand2 = factory(UserBrand::class)->create([
            'brand_id'      => $brand2->id,
            'user_id'       => $suscribedUserBothBrand->id,
            'unsubscribed'  => 0
        ]);
        $suscribedBothUserHotel2 = factory(UserHotel::class)->create([
            'id_usuario' => $suscribedUserBothBrand->id,
            'id_hotel' =>  $hotel2->id,
            'unsubscribed' => 0
        ]);

        $unsubscribedUserBrand = factory(UserBrand::class)->create([
            'brand_id'      => $brand->id,
            'user_id'       => $unsubscribedUser->id,
            'unsubscribed'  => 1
        ]);
        $unsubscribedUserHotel = factory(UserHotel::class)->create([
            'id_usuario' => $unsubscribedUser->id,
            'id_hotel' =>  $hotel->id,
            'unsubscribed' => 1
        ]);

        $job = new ProcessBulkUnsubscribe($brand->id, [PHP_INT_MAX, $suscribedUserOtherBrand->id, $suscribedUser->id, $suscribedUserBothBrand->id, $unsubscribedUser->id]);
        $job->handle(
            app('App\Services\Users\UserService')
        );

        $this->assertEquals($suscribedUserBrand->fresh()->unsubscribed, 1);
        $this->assertEquals($suscribedUserHotel->fresh()->unsubscribed, 1);

        $this->assertEquals($suscribedUserOtherUserBrand->fresh()->unsubscribed, 0);
        $this->assertEquals($suscribedUserOtherUserHotel->fresh()->unsubscribed, 0);

        $this->assertEquals($suscribedBothUserBrand->fresh()->unsubscribed, 1);
        $this->assertEquals($suscribedBothUserHotel->fresh()->unsubscribed, 1);
        $this->assertEquals($suscribedBothUserBrand2->fresh()->unsubscribed, 0);
        $this->assertEquals($suscribedBothUserHotel2->fresh()->unsubscribed, 0);

        $this->assertEquals($unsubscribedUserBrand->fresh()->unsubscribed, 1);
        $this->assertEquals($unsubscribedUserHotel->fresh()->unsubscribed, 1);
    }
}
