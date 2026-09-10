<?php

namespace Tests\Feature;

use App\Brand;
use App\Connection;
use App\Device;
use App\UserBrand;
use App\UserHotel;
use App\Hotel;
use App\HotelReview;
use App\Jobs\ProcessSnsEvent;
use App\Services\Connections\ConnectionTest;
use App\User;
use App\Visit;
use Tests\TestCase;

class WorkerVisitEventsTest extends TestCase
{
    private $brand;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $hotel = factory(Hotel::class)->create();
        $this->brand = Brand::where('hotel_id', $hotel->id)->first();
        $this->user = factory(User::class)->create();

        factory(UserHotel::class)->create(['id_usuario' => $this->user->id, 'id_hotel' => $hotel->id, 'unsubscribed' => 0]);
        factory(UserBrand::class)->create(['user_id' => $this->user->id, 'brand_id' => $this->brand->id, 'unsubscribed' => 0]);
    }

    public function testFormUserConnected()
    {
        $connection = new ConnectionTest();
        $event = new ProcessSnsEvent('{"schema":"com.hotelinking/Users/user_connected/2.0.0","origin":"hotelinking/' . $this->brand->id . '","originalEntity":"hotelinking","eventSource":"http://local.hotelinking.com/stay-wifi-redirect/?i=3&u=4168&PHPSESSID=vh2h0lakv5c2roevaoq9uillcb","context":{"brandID":' . $this->brand->id . '},"payload":{"brand":{"id":' . $this->brand->id . '},"roomID":null,"user":{"id":' . $this->user->id . ',"email":"test@hotelinking.com","name":"TEST","lang":"es","gender":"male","birthday":"1990-04-04","locale":"es_ES","isNew":false,"regularUser":false,"stayTimeReconnection":false,"source":"form","sendex":0.7,"result":"deliverable","is_client":true},"device":{"mac":null,"userAgent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36","acceptLanguage":"es-ES,es;q=0.9,de;q=0.8,en;q=0.7,ca;q=0.6"}}}', $connection);

        $event->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(!in_array("com.hotelinking/Emails/send_review_email/2.0.0", $eventsEmitted), 1, $connection->getValidationErrors());

        $visit = Visit::first();
        $this->assertEquals($visit->userBrand->brand_id, $this->brand->id);
        $this->assertEquals($visit->userBrand->user_id, $this->user->id);
        $this->assertEquals($visit->brand_id, $this->brand->id);
        $this->assertEquals($visit->user_id, $this->user->id);
        $this->assertReservationConnections($visit);
        $this->assertEquals(count(json_decode($visit->reservation, true)), 1);

        $device = Device::first();
        $this->assertEquals($device->device_family, "Mac");

        $connection = Connection::first();
        $this->assertEquals($connection->browser, "Chrome");
        $this->assertEquals($connection->browser_version, "77.0.3865");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->operating_system, "Mac OS X");
        $this->assertEquals($connection->operating_system_version, "10.14.0");
        $this->assertEquals($connection->device_id, $device->id);
        $this->assertEquals($connection->visit_id, $visit->id);
        $this->assertEquals($connection->access_type_id, 1);
    }

    public function testFacebookUserConnected()
    {
        $connection = new ConnectionTest();
        $event = new ProcessSnsEvent('{"schema":"com.hotelinking/Users/user_connected/2.0.0","origin":"hotelinking/' . $this->brand->id . '","originalEntity":"hotelinking","eventSource":"http://local.hotelinking.com/stay-wifi-redirect/?i=3&u=4168&PHPSESSID=vh2h0lakv5c2roevaoq9uillcb","context":{"brandID":' . $this->brand->id . '},"payload":{"brand":{"id":' . $this->brand->id . '},"roomID":null,"user":{"id":' . $this->user->id . ',"email":"test@hotelinking.com","name":"TEST","lang":"es","gender":"male","birthday":"1990-04-04","locale":"es_ES","isNew":false,"regularUser":false,"stayTimeReconnection":false,"source":"Facebook","sendex":0.7,"result":"deliverable","is_client":true},"device":{"mac":null,"userAgent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36","acceptLanguage":"es-ES,es;q=0.9,de;q=0.8,en;q=0.7,ca;q=0.6"}}}', $connection);

        $event->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(!in_array("com.hotelinking/Emails/send_review_email/2.0.0", $eventsEmitted), 1, $connection->getValidationErrors());

        $visit = Visit::first();
        $this->assertEquals($visit->userBrand->brand_id, $this->brand->id);
        $this->assertEquals($visit->userBrand->user_id, $this->user->id);
        $this->assertEquals($visit->brand_id, $this->brand->id);
        $this->assertEquals($visit->user_id, $this->user->id);
        $this->assertReservationConnections($visit);

        $device = Device::first();
        $this->assertEquals($device->device_family, "Mac");

        $connection = Connection::first();
        $this->assertEquals($connection->browser, "Chrome");
        $this->assertEquals($connection->browser_version, "77.0.3865");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->operating_system, "Mac OS X");
        $this->assertEquals($connection->operating_system_version, "10.14.0");
        $this->assertEquals($connection->device_id, $device->id);
        $this->assertEquals($connection->visit_id, $visit->id);
        $this->assertEquals($connection->access_type_id, 2);
    }

    public function testGoogleUserConnected()
    {
        $connection = new ConnectionTest();
        $event = new ProcessSnsEvent('{"schema":"com.hotelinking/Users/user_connected/2.0.0","origin":"hotelinking/' . $this->brand->id . '","originalEntity":"hotelinking","eventSource":"http://local.hotelinking.com/stay-wifi-redirect/?i=3&u=4168&PHPSESSID=vh2h0lakv5c2roevaoq9uillcb","context":{"brandID":' . $this->brand->id . '},"payload":{"brand":{"id":' . $this->brand->id . '},"roomID":null,"user":{"id":' . $this->user->id . ',"email":"test@hotelinking.com","name":"TEST","lang":"es","gender":"male","birthday":"1990-04-04","locale":"es_ES","isNew":false,"regularUser":false,"stayTimeReconnection":false,"source":"Google","sendex":0.7,"result":"deliverable","is_client":true},"device":{"mac":null,"userAgent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36","acceptLanguage":"es-ES,es;q=0.9,de;q=0.8,en;q=0.7,ca;q=0.6"}}}', $connection);

        $event->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(!in_array("com.hotelinking/Emails/send_review_email/2.0.0", $eventsEmitted), 1, $connection->getValidationErrors());

        $visit = Visit::first();
        $this->assertEquals($visit->userBrand->brand_id, $this->brand->id);
        $this->assertEquals($visit->userBrand->user_id, $this->user->id);
        $this->assertEquals($visit->brand_id, $this->brand->id);
        $this->assertEquals($visit->user_id, $this->user->id);
        $this->assertReservationConnections($visit);

        $device = Device::first();
        $this->assertEquals($device->device_family, "Mac");

        $connection = Connection::first();
        $this->assertEquals($connection->browser, "Chrome");
        $this->assertEquals($connection->browser_version, "77.0.3865");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->operating_system, "Mac OS X");
        $this->assertEquals($connection->operating_system_version, "10.14.0");
        $this->assertEquals($connection->device_id, $device->id);
        $this->assertEquals($connection->visit_id, $visit->id);
        $this->assertEquals($connection->access_type_id, 3);
    }

    public function testUserConnectedWithBadSource()
    {
        $connection = new ConnectionTest();
        $c = new ProcessSnsEvent('{"schema":"com.hotelinking/Users/user_connected/2.0.0","origin":"hotelinking/' . $this->brand->id . '","originalEntity":"hotelinking","eventSource":"http://local.hotelinking.com/stay-wifi-redirect/?i=3&u=4168&PHPSESSID=vh2h0lakv5c2roevaoq9uillcb","context":{"brandID":' . $this->brand->id . '},"payload":{"brand":{"id":' . $this->brand->id . '},"roomID":null,"user":{"id":' . $this->user->id . ',"email":"test@hotelinking.com","name":"TEST","lang":"es","gender":"male","birthday":"1990-04-04","locale":"es_ES","isNew":false,"regularUser":false,"stayTimeReconnection":false,"source":"BadSourceRetrieveForm","sendex":0.7,"result":"deliverable","is_client":true},"device":{"mac":null,"userAgent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36","acceptLanguage":"es-ES,es;q=0.9,de;q=0.8,en;q=0.7,ca;q=0.6"}}}', $connection);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(!in_array("com.hotelinking/Emails/send_review_email/2.0.0", $eventsEmitted), 1, $connection->getValidationErrors());

        $visit = Visit::first();
        $this->assertEquals($visit->userBrand->brand_id, $this->brand->id);
        $this->assertEquals($visit->userBrand->user_id, $this->user->id);
        $this->assertEquals($visit->brand_id, $this->brand->id);
        $this->assertEquals($visit->user_id, $this->user->id);
        $this->assertReservationConnections($visit);

        $device = Device::first();
        $this->assertEquals($device->device_family, "Mac");

        $connection = Connection::first();
        $this->assertEquals($connection->browser, "Chrome");
        $this->assertEquals($connection->browser_version, "77.0.3865");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->operating_system, "Mac OS X");
        $this->assertEquals($connection->operating_system_version, "10.14.0");
        $this->assertEquals($connection->device_id, $device->id);
        $this->assertEquals($connection->visit_id, $visit->id);
        $this->assertEquals($connection->access_type_id, 1);
    }

    public function testWithPmsReservation()
    {
        $connection = new ConnectionTest();
        $event = new ProcessSnsEvent('{"schema":"com.hotelinking/Users/user_connected/2.0.0","origin":"hotelinking/' . $this->brand->id . '","originalEntity":"hotelinking","eventSource":"http://local.hotelinking.com/stay-wifi-redirect/?i=3&u=4168&PHPSESSID=vh2h0lakv5c2roevaoq9uillcb","context":{"brandID":' . $this->brand->id . '},"payload":{"brand":{"id":' . $this->brand->id . '},"roomID":null,"user":{"id":' . $this->user->id . ',"email":"test@hotelinking.com","name":"TEST","lang":"es","gender":"male","birthday":"1990-04-04","locale":"es_ES","isNew":false,"regularUser":false,"stayTimeReconnection":false,"source":"form","sendex":0.7,"result":"deliverable","is_client":true},"device":{"mac":null,"userAgent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36","acceptLanguage":"es-ES,es;q=0.9,de;q=0.8,en;q=0.7,ca;q=0.6"},"pms_reservation":{"city":"Mock_City","email":"","gender":"male","pms_id":"1234","res_id":"MOCK_RES_ID_1","address":"MOCK_ADDRESS","percent":"100","birthday":"1993-10-10","brand_id":"1564","check_in":"2019-11-11","hotel_id":"1141","pax_type":"","province":"","res_data":"{\"market\":\"TI\",\"market_source\":\"TO\",\"market_source_2\":\"TO\",\"travel_point\":\"0\"}","res_date":"2019-01-25","check_out":"2019-11-13","last_name":"Mock_Lastname","res_board":"MOCK_RES_BOARD","telephone":"","first_name":"Mock_Firstname","hotel_name":"","res_adults":"2","res_agency":"DIRECT VOICE","res_amount":"100.00","res_babies":"","res_extras":"","res_nights":"2","document_id":"MOCK_DOCUMENT_ID","nationality":"MOCK_NATIONALITY","postal_code":"MOCK_POSTAL_CODE","res_channel":"TO","res_company":"","res_juniors":"","res_seniors":"","res_children":"0","res_comments":"pd","res_contract":"","res_currency":"","birth_country":"Mock_Birth_Country","res_room_type":"DB","loyalty_member":"","res_room_number":"123","res_intermediary":"","residence_country":""}}}', $connection);

        $event->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(!in_array("com.hotelinking/Emails/send_review_email/2.0.0", $eventsEmitted), 1, $connection->getValidationErrors());

        $visit = Visit::first();
        $this->assertEquals($visit->userBrand->brand_id, $this->brand->id);
        $this->assertEquals($visit->userBrand->user_id, $this->user->id);
        $this->assertEquals($visit->brand_id, $this->brand->id);
        $this->assertEquals($visit->user_id, $this->user->id);

        $reservationData = json_decode($visit->reservation, true);
        $this->assertArrayHasKey('pms_id', $reservationData);
        $this->assertEquals(count(json_decode($visit->reservation, true)), 45);
        $this->assertReservationConnections($visit);

        $device = Device::first();
        $this->assertEquals($device->device_family, "Mac");

        $connection = Connection::first();
        $this->assertEquals($connection->browser, "Chrome");
        $this->assertEquals($connection->browser_version, "77.0.3865");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->operating_system, "Mac OS X");
        $this->assertEquals($connection->operating_system_version, "10.14.0");
        $this->assertEquals($connection->device_id, $device->id);
        $this->assertEquals($connection->visit_id, $visit->id);
        $this->assertEquals($connection->access_type_id, 1);
    }

    public function testWithReviewAfterWifi()
    {
        factory(HotelReview::class)->create([
            "id_hotel"      => $this->brand->hotel->id,
            "ignoreRating"  => 1,
            "send_type"     => "after_wifi"
        ]);

        $connection = new ConnectionTest();
        $c = new ProcessSnsEvent('{"schema":"com.hotelinking/Users/user_connected/2.0.0","origin":"hotelinking/' . $this->brand->id . '","originalEntity":"hotelinking","eventSource":"http://local.hotelinking.com/stay-wifi-redirect/?i=3&u=4168&PHPSESSID=vh2h0lakv5c2roevaoq9uillcb","context":{"brandID":' . $this->brand->id . '},"payload":{"brand":{"id":' . $this->brand->id . '},"roomID":null,"user":{"id":' . $this->user->id . ',"email":"test@hotelinking.com","name":"TEST","lang":"es","gender":"male","birthday":"1990-04-04","locale":"es_ES","isNew":false,"regularUser":false,"stayTimeReconnection":false,"source":"BadSourceRetrieveForm","sendex":0.7,"result":"deliverable","is_client":true},"device":{"mac":null,"userAgent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36","acceptLanguage":"es-ES,es;q=0.9,de;q=0.8,en;q=0.7,ca;q=0.6"}}}', $connection);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(in_array("com.hotelinking/Emails/send_review_email/2.0.0", $eventsEmitted), 1, $connection->getValidationErrors());
        
        $visit = Visit::first();
        $this->assertEquals($visit->userBrand->brand_id, $this->brand->id);
        $this->assertEquals($visit->userBrand->user_id, $this->user->id);
        $this->assertEquals($visit->brand_id, $this->brand->id);
        $this->assertEquals($visit->user_id, $this->user->id);
        $this->assertReservationConnections($visit);

        $device = Device::first();
        $this->assertEquals($device->device_family, "Mac");

        $connection = Connection::first();
        $this->assertEquals($connection->browser, "Chrome");
        $this->assertEquals($connection->browser_version, "77.0.3865");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->operating_system, "Mac OS X");
        $this->assertEquals($connection->operating_system_version, "10.14.0");
        $this->assertEquals($connection->device_id, $device->id);
        $this->assertEquals($connection->visit_id, $visit->id);
        $this->assertEquals($connection->access_type_id, 1);
    }

    public function testWithReviewAfterCheckOut()
    {
        factory(HotelReview::class)->create([
            "id_hotel"      => $this->brand->hotel->id,
            "ignoreRating"  => 1,
            "send_type"     => "after_check_out"
        ]);

        $connection = new ConnectionTest();
        $c = new ProcessSnsEvent('{"schema":"com.hotelinking/Users/user_connected/2.0.0","origin":"hotelinking/' . $this->brand->id . '","originalEntity":"hotelinking","eventSource":"http://local.hotelinking.com/stay-wifi-redirect/?i=3&u=4168&PHPSESSID=vh2h0lakv5c2roevaoq9uillcb","context":{"brandID":' . $this->brand->id . '},"payload":{"brand":{"id":' . $this->brand->id . '},"roomID":null,"user":{"id":' . $this->user->id . ',"email":"test@hotelinking.com","name":"TEST","lang":"es","gender":"male","birthday":"1990-04-04","locale":"es_ES","isNew":false,"regularUser":false,"stayTimeReconnection":false,"source":"BadSourceRetrieveForm","sendex":0.7,"result":"deliverable","is_client":true},"device":{"mac":null,"userAgent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36","acceptLanguage":"es-ES,es;q=0.9,de;q=0.8,en;q=0.7,ca;q=0.6"}}}', $connection);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(in_array("com.hotelinking/Emails/send_review_email/2.0.0", $eventsEmitted), 1, $connection->getValidationErrors());
        
        $visit = Visit::first();
        $this->assertEquals($visit->userBrand->brand_id, $this->brand->id);
        $this->assertEquals($visit->userBrand->user_id, $this->user->id);
        $this->assertEquals($visit->brand_id, $this->brand->id);
        $this->assertEquals($visit->user_id, $this->user->id);
        $this->assertReservationConnections($visit);

        $device = Device::first();
        $this->assertEquals($device->device_family, "Mac");

        $connection = Connection::first();
        $this->assertEquals($connection->browser, "Chrome");
        $this->assertEquals($connection->browser_version, "77.0.3865");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->operating_system, "Mac OS X");
        $this->assertEquals($connection->operating_system_version, "10.14.0");
        $this->assertEquals($connection->device_id, $device->id);
        $this->assertEquals($connection->visit_id, $visit->id);
        $this->assertEquals($connection->access_type_id, 1);
    }

    public function testWithReviewAfterCheckOutAndNotIgnoreRating()
    {
        factory(HotelReview::class)->create([
            "id_hotel"      => $this->brand->hotel->id,
            "ignoreRating"  => 0,
            "send_type"     => "after_check_out"
        ]);

        $connection = new ConnectionTest();
        $c = new ProcessSnsEvent('{"schema":"com.hotelinking/Users/user_connected/2.0.0","origin":"hotelinking/' . $this->brand->id . '","originalEntity":"hotelinking","eventSource":"http://local.hotelinking.com/stay-wifi-redirect/?i=3&u=4168&PHPSESSID=vh2h0lakv5c2roevaoq9uillcb","context":{"brandID":' . $this->brand->id . '},"payload":{"brand":{"id":' . $this->brand->id . '},"roomID":null,"user":{"id":' . $this->user->id . ',"email":"test@hotelinking.com","name":"TEST","lang":"es","gender":"male","birthday":"1990-04-04","locale":"es_ES","isNew":false,"regularUser":false,"stayTimeReconnection":false,"source":"BadSourceRetrieveForm","sendex":0.7,"result":"deliverable","is_client":true},"device":{"mac":null,"userAgent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36","acceptLanguage":"es-ES,es;q=0.9,de;q=0.8,en;q=0.7,ca;q=0.6"}}}', $connection);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $this->assertEquals($schemaValidation, 1, $connection->getValidationErrors());
        $eventsEmitted = $connection->getEventsEmitted();
        $this->assertEquals(!in_array("com.hotelinking/Emails/send_review_email/2.0.0", $eventsEmitted), 1, $connection->getValidationErrors());
        
        $visit = Visit::first();
        $this->assertEquals($visit->userBrand->brand_id, $this->brand->id);
        $this->assertEquals($visit->userBrand->user_id, $this->user->id);
        $this->assertEquals($visit->brand_id, $this->brand->id);
        $this->assertEquals($visit->user_id, $this->user->id);
        $this->assertReservationConnections($visit);

        $device = Device::first();
        $this->assertEquals($device->device_family, "Mac");

        $connection = Connection::first();
        $this->assertEquals($connection->browser, "Chrome");
        $this->assertEquals($connection->browser_version, "77.0.3865");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->browser_lang, "es-ES,");
        $this->assertEquals($connection->operating_system, "Mac OS X");
        $this->assertEquals($connection->operating_system_version, "10.14.0");
        $this->assertEquals($connection->device_id, $device->id);
        $this->assertEquals($connection->visit_id, $visit->id);
        $this->assertEquals($connection->access_type_id, 1);
    }

    private function assertReservationConnections($visit)
    {
        $reservationData = json_decode($visit->reservation, true);
        $this->assertArrayHasKey('connections', $reservationData);
        $this->assertEquals(count($reservationData['connections']), 1);

        $firstConnection = $reservationData['connections'][0];

        // Assert keys in a connection
        $this->assertArrayHasKey('date', $firstConnection);
        $this->assertArrayHasKey('access_code', $firstConnection);
        $this->assertArrayHasKey('source', $firstConnection);
        $this->assertArrayHasKey('device', $firstConnection);
    
        // Assert keys in the 'device' sub-array
        $this->assertArrayHasKey('mac_address', $firstConnection['device']);
        $this->assertArrayHasKey('family', $firstConnection['device']);
        $this->assertArrayHasKey('brand', $firstConnection['device']);
        $this->assertArrayHasKey('model', $firstConnection['device']);
        $this->assertArrayHasKey('operating_system', $firstConnection['device']);
    }
}
