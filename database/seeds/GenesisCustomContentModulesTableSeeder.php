<?php

use Illuminate\Database\Seeder;
use App\CustomContentModule;

class GenesisCustomContentModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        CustomContentModule::updateOrCreate(
            ['id' => 1],
            [
                'custom_content_state_id' => 1,
                'custom_module_id'        => 1
            ]
        );

        CustomContentModule::updateOrCreate(
            ['id' => 2],
            [
                'custom_content_state_id' => 2,
                'custom_module_id'        => 2
            ]
        );

        CustomContentModule::updateOrCreate(
            ['id' => 13],
            [
                'custom_content_state_id' => 2,
                'custom_module_id'        => 10
            ]
        );

        CustomContentModule::updateOrCreate(
            ['id' => 3],
            [
                'custom_content_state_id' => 3,
                'custom_module_id'        => 3
            ]
        );

        CustomContentModule::updateOrCreate(
            ['id' => 4],
            [
                'custom_content_state_id' => 4,
                'custom_module_id'        => 4
            ]
        );

        CustomContentModule::updateOrCreate(
            ['id' => 5],
            [
                'custom_content_state_id' => 5,
                'custom_module_id'        => 5
            ]
        );

        CustomContentModule::updateOrCreate(
            ['id' => 6],
            [
                'custom_content_state_id' => 6,
                'custom_module_id'        => 6
            ]
        );

        CustomContentModule::updateOrCreate(
            ['id' => 10],
            [
                'custom_content_state_id' => 7,
                'custom_module_id'        => 7
            ]
        );

        CustomContentModule::updateOrCreate(
            ['id' => 11],
            [
                'custom_content_state_id' => 8,
                'custom_module_id'        => 8
            ]
        );

        CustomContentModule::updateOrCreate(
            ['id' => 12],
            [
                'custom_content_state_id' => 9,
                'custom_module_id'        => 9
            ]
        );

    }
}
