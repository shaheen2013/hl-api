<?php

use Illuminate\Database\Seeder;
use App\CustomModule;

class GenesisCustomModuleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        CustomModule::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'eprivacy_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 11],
            [
                'name' => 'eprivacy_text_informal'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'legal_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'not_hotel_eprivacy_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 9],
            [
                'name' => 'not_hotel_legal_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 8],
            [
                'name' => 'not_hotel_second_eprivacy_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 15],
            [
                'name' => 'not_hotel_second_eprivacy_text_informal'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'restrictive_eprivacy_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 13],
            [
                'name' => 'restrictive_eprivacy_text_informal'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'restrictive_legal_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'restrictive_second_eprivacy_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 14],
            [
                'name' => 'restrictive_second_eprivacy_text_informal'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 10],
            [
                'name' => 'second_eprivacy_not_client_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 16],
            [
                'name' => 'second_eprivacy_not_client_text_informal'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'second_eprivacy_text'
            ]
        );

        CustomModule::updateOrCreate(
            ['id' => 12],
            [
                'name' => 'second_eprivacy_text_informal'
            ]
        );

    }
}
