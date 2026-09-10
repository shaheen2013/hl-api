<?php

namespace Tests\Feature;

use App\Brand;
use App\Hotel;
use App\HotelGuid;
use App\HotelSatisfaction;
use App\BrandProtocol;
use App\BrandProduct;
use App\Jobs\ProcessSnsEvent;
use App\Services\Connections\ConnectionTest;
use App\User;
use App\UserGuid;
use App\Satisfaction;
use App\Offer;
use App\OfferLang;
use App\OfferGoal;
use App\LoyaltyConfig;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class WorkerBrandEventsTest extends TestCase
{
    protected $productService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productService = $this->app->make('App\Services\Products\ProductService');
    }


    public function testBrandVisitedEventForLoyalty()
    {
        $user = factory(User::class)->create(['nombre' => 'First User']);
        factory(UserGuid::class)->create(['id_usuario' => $user->id]);

        $hotel = factory(Hotel::class)->create();
        factory(HotelSatisfaction::class)->create(['id_hotel' => $hotel->id, 'customized_type' => 'In a later email', "customized_active" => 1]);
        factory(HotelGuid::class)->create(['id_hotel' => $hotel->id]);

        $brand = Brand::where('hotel_id', $hotel->id)->first();
        factory(BrandProtocol::class)->create(['brand_id' => $brand->id, 'service' => 'emails']);

        $loyaltyProduct = $this->productService->getByName('loyalty');
        $customizedSurveyProduct = $this->productService->getByName('customized_satisfaction_surveys');

        factory(BrandProduct::class)->create([
            'brand_id' => $brand->id,
            'product_id' => $loyaltyProduct->id,
            'active' => 1
        ]);

        factory(BrandProduct::class)->create([
            'brand_id' => $brand->id,
            'product_id' => $customizedSurveyProduct->id,
            'active' => 1
        ]);

        $offer = factory(Offer::class)->create(['id_hotel' => $hotel->id]);
        factory(OfferLang::class)->create(['id_oferta' => $offer->id]);
        factory(OfferGoal::class)->create(['offer_id' => $offer->id, 'brand_id' => $brand->id]);
        factory(LoyaltyConfig::class)->create(['brand_id' => $brand->id, 'summary_active' => 1]);


        $connection = new ConnectionTest();
        $c = new  ProcessSnsEvent('{"schema":"com.hotelinking/Brands/brand_visited/2.0.0","origin":"hotelinking/' . $brand->id . '","originalEntity":"hotelinking","eventSource":"http://localhost/stay-wifi-redirect/?i=4&u=20&PHPSESSID=58bb589b03796f54e3e045712631a4ba","context":{"brandID":' . $brand->id . ',"firstEventID":"91a82516-3b57-11e9-a918-2363b42081d3"},"payload":{"brand":{"id":' . $brand->id . '},"roomID":"1","user":{"id":' . $user->id . ',"email":"satis@satis.com","name":"A@qa.com","lang":"en","gender":"female","birthday":"2000-05-05","locale":"es_ES","isNew":false,"regularUser":true,"stayTimeReconnection":false,"source":"form","sendex":"0.00","result":"risky"},"numVisits":0},"eventID":"91a82516-3b57-11e9-a918-2363b42081d3"}', $connection);

        // No user satisfaction from the given user last 24 hours
        $c->handle();

        $schemaValidation = $connection->getValidation();
        $eventsEmitted = $connection->getEventsEmitted();

        Log::debug("This is my validation", [$schemaValidation]);
        Log::debug("This are my evenets emitted", [$eventsEmitted]);

        $this->assertEquals($schemaValidation, 1);
        $this->assertEquals(in_array("com.hotelinking/Emails/send_loyalty_summary_email/1.0.0", $eventsEmitted), 1);
        $this->assertEquals(in_array("com.hotelinking/Emails/send_customized_satisfaction_email/1.0.0", $eventsEmitted), 0);

        // With user satisfaction created in the last 24 hours
        $satisfaction = factory(Satisfaction::class)->create([
            'id_usuario'            => $user->id,
            'id_hotel'              => $brand->hotel_id,
            'fecha_creado'          => Carbon::now(),
            'customized_send_date'  => Carbon::now()->addDays(1),
        ]);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $eventsEmitted = $connection->getEventsEmitted();

        $this->assertEquals($schemaValidation, 1);
        $this->assertEquals(in_array("com.hotelinking/Emails/send_loyalty_summary_email/1.0.0", $eventsEmitted), 1);
        $this->assertEquals(in_array("com.hotelinking/Emails/send_customized_satisfaction_email/1.0.0", $eventsEmitted), 1);
    }

    public function testBrandVisitedEventNoSendCustomizedEmailIfJoinedWithSatisfaction()
    {
        $user = factory(User::class)->create(['nombre' => 'First User']);
        factory(UserGuid::class)->create(['id_usuario' => $user->id]);

        $hotel = factory(Hotel::class)->create();
        factory(HotelSatisfaction::class)->create(['id_hotel' => $hotel->id, 'customized_type' => 'Joined with satisfaction', "customized_active" => 1]);
        factory(HotelGuid::class)->create(['id_hotel' => $hotel->id]);

        $brand = Brand::where('hotel_id', $hotel->id)->first();
        factory(BrandProtocol::class)->create(['brand_id' => $brand->id, 'service' => 'emails']);

        $customizedSurveyProduct = $this->productService->getByName('customized_satisfaction_surveys');

        factory(BrandProduct::class)->create([
            'brand_id' => $brand->id,
            'product_id' => $customizedSurveyProduct->id,
            'active' => 1
        ]);

        $connection = new ConnectionTest();
        $c = new  ProcessSnsEvent('{"schema":"com.hotelinking/Brands/brand_visited/2.0.0","origin":"hotelinking/' . $brand->id . '","originalEntity":"hotelinking","eventSource":"http://localhost/stay-wifi-redirect/?i=4&u=20&PHPSESSID=58bb589b03796f54e3e045712631a4ba","context":{"brandID":' . $brand->id . ',"firstEventID":"91a82516-3b57-11e9-a918-2363b42081d3"},"payload":{"brand":{"id":' . $brand->id . '},"roomID":"1","user":{"id":' . $user->id . ',"email":"satis@satis.com","name":"A@qa.com","lang":"en","gender":"female","birthday":"2000-05-05","locale":"es_ES","isNew":false,"regularUser":true,"stayTimeReconnection":false,"source":"form","sendex":"0.00","result":"risky"},"numVisits":0},"eventID":"91a82516-3b57-11e9-a918-2363b42081d3"}', $connection);

        // With user satisfaction created in the last 24 hours
        $satisfaction = factory(Satisfaction::class)->create([
            'id_usuario'            => $user->id,
            'id_hotel'              => $brand->hotel_id,
            'fecha_creado'          => Carbon::now(),
            'customized_send_date'  => Carbon::now()->addDays(1),
        ]);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $eventsEmitted = $connection->getEventsEmitted();

        $this->assertEquals($schemaValidation, 1);
        $this->assertEquals(in_array("com.hotelinking/Emails/send_loyalty_summary_email/1.0.0", $eventsEmitted), 0);
        $this->assertEquals(in_array("com.hotelinking/Emails/send_customized_satisfaction_email/1.0.0", $eventsEmitted), 0);
    }

    public function testBrandVisitedEventNoSendCustomizedEmailIfSenddateAndCustomizedSendDateAreEqual()
    {
        $user = factory(User::class)->create(['nombre' => 'First User']);
        factory(UserGuid::class)->create(['id_usuario' => $user->id]);

        $hotel = factory(Hotel::class)->create();
        factory(HotelSatisfaction::class)->create(['id_hotel' => $hotel->id, 'customized_type' => 'After PMS checkout date', "customized_active" => 1]);
        factory(HotelGuid::class)->create(['id_hotel' => $hotel->id]);

        $brand = Brand::where('hotel_id', $hotel->id)->first();
        factory(BrandProtocol::class)->create(['brand_id' => $brand->id, 'service' => 'emails']);

        $customizedSurveyProduct = $this->productService->getByName('customized_satisfaction_surveys');

        factory(BrandProduct::class)->create([
            'brand_id' => $brand->id,
            'product_id' => $customizedSurveyProduct->id,
            'active' => 1
        ]);

        $connection = new ConnectionTest();
        $c = new  ProcessSnsEvent('{"schema":"com.hotelinking/Brands/brand_visited/2.0.0","origin":"hotelinking/' . $brand->id . '","originalEntity":"hotelinking","eventSource":"http://localhost/stay-wifi-redirect/?i=4&u=20&PHPSESSID=58bb589b03796f54e3e045712631a4ba","context":{"brandID":' . $brand->id . ',"firstEventID":"91a82516-3b57-11e9-a918-2363b42081d3"},"payload":{"brand":{"id":' . $brand->id . '},"roomID":"1","user":{"id":' . $user->id . ',"email":"satis@satis.com","name":"A@qa.com","lang":"en","gender":"female","birthday":"2000-05-05","locale":"es_ES","isNew":false,"regularUser":true,"stayTimeReconnection":false,"source":"form","sendex":"0.00","result":"risky"},"numVisits":0},"eventID":"91a82516-3b57-11e9-a918-2363b42081d3"}', $connection);

        $now = Carbon::now();
        $satisfaction = factory(Satisfaction::class)->create([
            'id_usuario'            => $user->id,
            'id_hotel'              => $brand->hotel_id,
            'fecha_creado'          => $now,
            'send_date'             => $now,
            'customized_send_date'  => $now
        ]);

        $c->handle();

        $schemaValidation = $connection->getValidation();
        $eventsEmitted = $connection->getEventsEmitted();

        $this->assertEquals($schemaValidation, 1);
        $this->assertEquals(in_array("com.hotelinking/Emails/send_loyalty_summary_email/1.0.0", $eventsEmitted), 0);
        $this->assertEquals(in_array("com.hotelinking/Emails/send_customized_satisfaction_email/1.0.0", $eventsEmitted), 0);
    }
}
