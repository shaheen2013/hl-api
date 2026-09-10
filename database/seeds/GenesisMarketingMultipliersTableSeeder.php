<?php

use Illuminate\Database\Seeder;

class GenesisMarketingMultipliersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('marketing_multipliers')->delete();

        \DB::table('marketing_multipliers')->insert([
            0 =>
                [
                    'impressions_percents'       => 12,
                    'single_click_price'         => 2.3,
                    'thousand_impressions_price' => 7.5,
                ],
        ]);
    }
}
