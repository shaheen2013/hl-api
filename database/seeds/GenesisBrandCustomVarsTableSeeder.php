<?php

use Illuminate\Database\Seeder;
use App\BrandCustomVar;

class GenesisBrandCustomVarsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        BrandCustomVar::updateOrCreate(
            ['id' => 1],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'custom_vars_id' => 1,
                'value'          => 'Hotelinking S.L.'
            ]
        );

        BrandCustomVar::updateOrCreate(
            ['id' => 2],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'custom_vars_id' => 2,
                'value'          => 'Gremi Cirurgians i Barbers 48, 2F, 07009, Palma de Mallorca, España'
            ]
        );

        BrandCustomVar::updateOrCreate(
            ['id' => 3],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'custom_vars_id' => 3,
                'value'          => 'B57943185'
            ]
        );

        BrandCustomVar::updateOrCreate(
            ['id' => 4],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'custom_vars_id' => 4,
                'value'          => 'dpo@hotelinking.com'
            ]
        );

        BrandCustomVar::updateOrCreate(
            ['id' => 5],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'custom_vars_id' => 10,
                'value'          => '16'
            ]
        );
    }
}
