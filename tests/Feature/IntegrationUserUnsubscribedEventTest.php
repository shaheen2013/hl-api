<?php

namespace Tests\Feature;

use App\Jobs\SqsHandlerEvent;
use App\Hotel;
use App\Brand;
use App\User;
use App\UserBrand;
use App\UserHotel;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;

class IntegrationUserUnsubscribedEventTest extends TestCase
{
    protected $expectedResponse;
    protected $userBrands;
    protected $userHotels;
    protected $payload;
    protected $brandService;
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->brandService = $this->app->make('App\Services\BrandService');

        $this->userService = $this->app->make('App\Services\Users\UserService');

        $user = factory(User::class)->create();
        $hotel = factory(Hotel::class)->create();
        $brandID = factory(Brand::class)->create([
            'hotel_id' => $hotel->id,
            'chain_id' => null,
            'brand_type_id' => 2
        ]);

        $this->userBrands = factory(UserBrand::class)->create([
            'user_id' => $user->id,
            'brand_id' => $brandID->id,
            'unsubscribed' => 0
        ]);

        $this->userHotels = factory(UserHotel::class)->create([
            'id_usuario' => $user->id,
            'id_hotel' =>  $hotel->id,
            'unsubscribed' => 0
        ]);


        $this->payload = [
            'detail-type' => 'integration_user_unsubscribed',
            'detail' => [
                'user' => [
                    'id' => $this->userBrands->user_id
                ],
                'brands' => [$this->userBrands->brand_id]
            ]
        ];
    }


    public function testIntegrationUserUnsubscribedEventBodyIsExpected()
    {
        $this->assertNotEmpty($this->payload);
        $this->assertArrayHasKey('detail-type', $this->payload);
        $this->assertEquals($this->payload['detail-type'], 'integration_user_unsubscribed');
        $this->assertArrayHasKey('detail', $this->payload);
        $this->assertNotEmpty($this->payload['detail']);
        $this->assertNotEmpty($this->payload['detail']['brands']);
    }

    public function testEventIsReceivedAndDatabaseUpdated()
    {
        // Create a new Job
        $event = new SqsHandlerEvent($this->userService);

        $event->integrationUserUnsubscribed($this->payload);

        // Assert database has been updated
        $this->assertDatabaseHas('user_hotels', [
            'id_usuario' => $this->userHotels->id_usuario,
            'id_hotel' =>  $this->userHotels->id_hotel,
            'unsubscribed' => 1
        ]);
        $this->assertDatabaseHas('new_user_brand', [
            'user_id' => $this->userBrands->user_id,
            'brand_id' =>  $this->userBrands->brand_id,
            'unsubscribed' => 1
        ]);
    }

    public function testEventIsDispatchedAndUserIsAlreadyUnsubscribed()
    {


        $this->userBrands->update(["unsubscribed", 1]);
        // Create a new Job
        $event = new SqsHandlerEvent($this->userService);

        $event->integrationUserUnsubscribed($this->payload);

        // Assert database is already updated

        Log::shouldReceive('info')
            ->with('User already unsubscribed');

        $this->assertDatabaseHas('new_user_brand', [
            'user_id' => $this->userBrands->user_id,
            'brand_id' =>  $this->userBrands->brand_id,
            'unsubscribed' => 1
        ]);
    }

    public function testEventIsDispatched()
    {
        Queue::fake();

        // Assert that no jobs were pushed.
        Queue::assertNothingPushed();

        // Act
        SqsHandlerEvent::dispatch($this->userService);

        // Job is dispatched correctly.
        Queue::assertPushed(SqsHandlerEvent::class, function () {
            return true;
        });
    }
}
