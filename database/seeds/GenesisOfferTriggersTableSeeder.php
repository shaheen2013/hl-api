<?php

use Illuminate\Database\Seeder;
use App\OfferTrigger;

class GenesisOfferTriggersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        OfferTrigger::updateOrCreate(
            ['id' => 1],
            [
                'type' => 'wifi'
            ]
        );

        OfferTrigger::updateOrCreate(
            ['id' => 2],
            [
                'type' => 'facebook_login'
            ]
        );

        OfferTrigger::updateOrCreate(
            ['id' => 3],
            [
                'type' => 'facebook_share'
            ]
        );

        OfferTrigger::updateOrCreate(
            ['id' => 4],
            [
                'type' => 'loyalty'
            ]
        );
    }
}
