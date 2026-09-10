<?php

use Illuminate\Database\Seeder;
use App\CustomModuleText;

class GenesisCustomModuleTextsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        CustomModuleText::updateOrCreate(
            ['id' => 1],
            [
                'custom_module_id' => 1,
                'custom_text_id'   => 1
            ]
        );

        CustomModuleText::updateOrCreate(
            ['id' => 2],
            [
                'custom_module_id' => 2,
                'custom_text_id'   => 1
            ]
        );

        CustomModuleText::updateOrCreate(
            ['id' => 3],
            [
                'custom_module_id' => 3,
                'custom_text_id'   => 1
            ]
        );

        CustomModuleText::updateOrCreate(
            ['id' => 4],
            [
                'custom_module_id' => 4,
                'custom_text_id'   => 1
            ]
        );

        CustomModuleText::updateOrCreate(
            ['id' => 5],
            [
                'custom_module_id' => 5,
                'custom_text_id'   => 1
            ]
        );

        CustomModuleText::updateOrCreate(
            ['id' => 6],
            [
                'custom_module_id' => 6,
                'custom_text_id'   => 1
            ]
        );

        CustomModuleText::updateOrCreate(
            ['id' => 7],
            [
                'custom_module_id' => 7,
                'custom_text_id'   => 1
            ]
        );

        CustomModuleText::updateOrCreate(
            ['id' => 8],
            [
                'custom_module_id' => 8,
                'custom_text_id'   => 1
            ]
        );

        CustomModuleText::updateOrCreate(
            ['id' => 9],
            [
                'custom_module_id' => 9,
                'custom_text_id'   => 1
            ]
        );

        CustomModuleText::updateOrCreate(
            ['id' => 10],
            [
                'custom_module_id' => 10,
                'custom_text_id'   => 1
            ]
        );

    }
}
