<?php

use Illuminate\Database\Seeder;
use App\CustomContent;

class GenesisCustomContentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        CustomContent::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'first_eprivacy_page'
            ]
        );

        CustomContent::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'legal_text'
            ]
        );

        CustomContent::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'second_eprivacy_page'
            ]
        );

    }
}
