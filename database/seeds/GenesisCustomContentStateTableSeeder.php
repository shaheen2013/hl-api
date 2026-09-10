<?php

use Illuminate\Database\Seeder;
use App\CustomContentState;

class GenesisCustomContentStateTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        CustomContentState::updateOrCreate(
            ['id' => 1],
            [
                'custom_content_id' => 1,
                'name'              => 'classic'
            ]
        );

        CustomContentState::updateOrCreate(
            ['id' => 2],
            [
                'custom_content_id' => 2,
                'name'              => 'classic'
            ]
        );

        CustomContentState::updateOrCreate(
            ['id' => 3],
            [
                'custom_content_id' => 3,
                'name'              => 'classic'
            ]
        );

        CustomContentState::updateOrCreate(
            ['id' => 4],
            [
                'custom_content_id' => 1,
                'name'              => 'restrictive'
            ]
        );

        CustomContentState::updateOrCreate(
            ['id' => 5],
            [
                'custom_content_id' => 2,
                'name'              => 'restrictive'
            ]
        );

        CustomContentState::updateOrCreate(
            ['id' => 6],
            [
                'custom_content_id' => 3,
                'name'              => 'restrictive'
            ]
        );

        CustomContentState::updateOrCreate(
            ['id' => 7],
            [
                'custom_content_id' => 1,
                'name'              => 'restrictive_not_hotel'
            ]
        );

        CustomContentState::updateOrCreate(
            ['id' => 8],
            [
                'custom_content_id' => 2,
                'name'              => 'restrictive_not_hotel'
            ]
        );

        CustomContentState::updateOrCreate(
            ['id' => 9],
            [
                'custom_content_id' => 3,
                'name'              => 'restrictive_not_hotel'
            ]
        );
    }
}
