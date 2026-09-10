<?php

namespace Tests\Feature;

use App\DeviceBlacklist;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceBlacklistTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetBlacklist()
    {
        $blackList = factory(DeviceBlacklist::class)->create();

        $this->json('GET', "/api/device/blacklist")
            ->assertStatus(200)
            ->assertJsonFragment(
                [
                    'device' => $blackList->device
                ]
            );
    }
}
