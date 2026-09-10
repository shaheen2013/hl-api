<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class HotelSeederCommandTest extends TestCase
{
    public function testHotelWithIntegrationCreated()
    {
        Artisan::call('hotelinking:hotel', [
            'email' => 'sally@example.com',
            'integration' => true,
        ]);

        $this->assertDatabaseHas('hoteles', [
            'email' => 'sally@example.com'
        ]);

        $this->assertDatabaseHas('hotel_wifi_integrations', [
            'wifi_id' => '9'
        ]);
    }

    public function testHotelWithoutIntegrationCreated()
    {
        Artisan::call('hotelinking:hotel', [
            'email' => 'sally@example.com',
            'integration' => false,
        ]);

        $this->assertDatabaseHas('hoteles', [
            'email' => 'sally@example.com'
        ]);

        $this->assertDatabaseMissing('hotel_wifi_integrations', [
            'wifi_id' => '9'
        ]);
    }
}
