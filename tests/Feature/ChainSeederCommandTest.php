<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ChainSeederCommandTest extends TestCase
{
    public function testChainCreation()
    {
        Artisan::call('hotelinking:chain', [
            'email' => 'sally@example.com',
        ]);

        $this->assertDatabaseHas('cadena', [
            'email' => 'sally@example.com'
        ]);
    }
}
