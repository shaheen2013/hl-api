<?php

namespace Tests;

use Aws\EventBridge\EventBridgeClient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $mockEventBridgeClient = Mockery::mock(EventBridgeClient::class);
        $mockEventBridgeClient->shouldReceive('putEvents')
        ->andReturn(collect([
            'Entries' => [['EventId' => 'mock-event-id']]
        ]));
        
        $this->app->instance(EventBridgeClient::class, $mockEventBridgeClient);
    }
}
