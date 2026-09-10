<?php

use Illuminate\Database\Seeder;
use App\OfferPlatform;

class GenesisOfferPlatformsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        OfferPlatform::updateOrCreate(
            ['id' => 1],
            [
                'type' => 'hotel'
            ]
        );

        OfferPlatform::updateOrCreate(
            ['id' => 2],
            [
                'type' => 'web'
            ]
        );
    }
}
