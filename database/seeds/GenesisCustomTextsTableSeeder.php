<?php

use Illuminate\Database\Seeder;
use App\CustomText;

class GenesisCustomTextsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        CustomText::updateOrCreate(
            ['id' => 1],
            [
                'name'        => '{{EprivacyPolicy}}',
                'description' => ''
            ]
        );
    }
}
