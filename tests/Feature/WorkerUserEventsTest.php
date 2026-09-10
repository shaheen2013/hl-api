<?php

namespace Tests\Feature;

use App\Brand;
use App\GdprHistory;
use App\UserHotel;
use App\UserBrand;
use App\Hotel;
use App\SocialMediaShare;
use App\UserGuid;
use App\Jobs\ProcessSnsEvent;
use App\Services\Connections\ConnectionTest;
use App\User;
use Tests\TestCase;

class WorkerUserEventsTest extends TestCase
{
    public function testUserUnsubscribed()
    {
        $user = factory(User::class)->create();
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', $hotel->id)->first();
        $userHotel = factory(UserHotel::class)->create(['id_usuario' => $user->id, 'id_hotel' => $hotel->id, 'unsubscribed' => 0]);
        $userBrand = factory(UserBrand::class)->create(['brand_id' => $brand->id, 'user_id' => $user->id, 'unsubscribed' => 0]);
        $connection = new ConnectionTest();
        $c = new  ProcessSnsEvent('{"schema":"com.hotelinking/User/user_unsubscribed/2.0.0","origin":"hotelinking/' . $brand->id . '","originalEntity":"hotelinking","eventSource":"https://app.hotelinking.com/app/users/cda4e376-c699-4061-b0b5-7cebcb77ce8e/bb41b080-2afb-41e3-afda-e1c0f5ea3c60/unsubscribe","context":{"brandID":' . $brand->id . ',"firstEventID":"d037e127-6b1c-11e9-b723-dd9f1ddf93ec"},"payload":{"userEmailUnsuscribed":"' . $user->email . '","user":{"id":' . $user->id . '}},"eventID":"d037e127-6b1c-11e9-b723-dd9f1ddf93ec"}', $connection);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1);
        $this->assertEquals($userHotel->fresh()->unsubscribed, 1);

        // Duplicate event will not change result
        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1);
        $this->assertEquals($userHotel->fresh()->unsubscribed, 1);
        $this->assertEquals($userBrand->fresh()->unsubscribed, 1);
    }

    public function testCreateUser()
    {
        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', $hotel->id)->first();
        $connection = new ConnectionTest();
        $c = new ProcessSnsEvent('{"context": {"brandID": ' . $brand->id . ', "cookieID": "1548839863116_f1tavholv350b47ec-1e7d-11e9-b623-0242ac160006"},"eventSource": "https://hotelinking.com","origin": "widget/350b47ec-1e7d-11e9-b623-0242ac160006","originalEntity": "widget","payload": {"firstName": "testSuccess", "lastName": "", "email": "xxxtrickshotxxx@gmail.com","emailDeliverability": "deliverable","emailSendex": 0.9 , "gender": "male", "locale": "es-ES","localeFacebook": "","origin": "form","unsuscribed": 0},"schema": "com.hotelinking/Users/create_user/1.0.0"}', $connection);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1);

        $user = User::where('email', 'xxxtrickshotxxx@gmail.com')->first();
        $userGuid = UserGuid::where('id_usuario', $user->id)->first();
        $this->assertEquals($user->name, 'testSuccess ');
        $this->assertNotEquals($userGuid, null);
        $this->assertNotEquals($userGuid->guid, '');

        //        duplicate event will not change result


        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1);
        $this->assertEquals($user->name, 'testSuccess ');
    }

    public function testGDPRAccepted()
    {
        $user = factory(User::class)->create([
            'nombre' => 'First User'
        ]);

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', $hotel->id)->first();
        $connection = new ConnectionTest();
        $c = new  ProcessSnsEvent('{"schema": "com.hotelinking/Form/gdpr_accepted/1.0.0","context": {"brandID": ' . $brand->id . ', "cookieID": "1549362481981_zo7ves350350b47ec-1e7d-11e9-b623-0242ac160006"},"eventSource": "https://hotelinking.com","origin": "widget/350b47ec-1e7d-11e9-b623-0242ac160006","originalEntity": "widget","payload": {"userID":' . $user->id . ',"cookieID": "1549362481981_zo7ves350350b47ec-1e7d-11e9-b623-0242ac160006", "accepted": "true", "acceptedDate": "2019-02-05T10:28:13.363Z"}}', $connection);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1);
        $gdpr = GdprHistory::where('user_id', $user->id)->get();
        $this->assertNotEquals($gdpr, null);

        //        duplicate event will not change result
        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1);

        $gdpr = GdprHistory::where('user_id', $user->id)->get();
        $this->assertNotEquals($gdpr, null);
    }

    public function testsaveUserSocialMediaShare()
    {
        $user = factory(User::class)->create([
            'nombre' => 'First User'
        ]);

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', $hotel->id)->first();

        $userBrand = factory(UserBrand::class)->create([
            'brand_id'  => $brand->id,
            'user_id'   => $user->id,
        ]);

        $connection = new ConnectionTest();
        $c = new  ProcessSnsEvent('{"context": {"brandID": ' . $brand->id . '},"eventSource": "https://hotelinking.com","origin": "hotelinking/4","originalEntity": "hotelinking","payload": {"brand":{"id":' . $brand->id . '},"user":{"id":' . $user->id . '},"share":{"type":3,"socialMedia":"Facebook"}},"schema": "com.hotelinking/Users/social_media_share/1.0.0"}', $connection);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1);

        $socialMediaShare = SocialMediaShare::where('user_brand_id', $userBrand->id)->first();
        $this->assertNotEquals($socialMediaShare, null);
    }
}
