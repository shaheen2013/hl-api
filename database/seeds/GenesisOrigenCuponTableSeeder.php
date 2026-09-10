<?php

use Illuminate\Database\Seeder;
use App\SourceCoupon;

class GenesisOrigenCuponTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        SourceCoupon::updateOrCreate(
            ['id' => 1],
            [
                'name'      => 'wifi',
                'origen_es' => 'during-stay',
                'origen_en' => 'during-stay'
            ]
        );

        SourceCoupon::updateOrCreate(
            ['id' => 2],
            [
                'name'      => 'birthday',
                'origen_es' => 'cumpleaños',
                'origen_en' => 'birthday'
            ]
        );

        SourceCoupon::updateOrCreate(
            ['id' => 3],
            [
                'name'      => 'cloyalty',
                'origen_es' => '',
                'origen_en' => ''
            ]
        );
    }
}
