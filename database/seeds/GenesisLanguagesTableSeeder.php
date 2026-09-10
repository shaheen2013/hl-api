<?php

use Illuminate\Database\Seeder;
use App\Language;

class GenesisLanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Language::updateOrCreate(
            ['id' => 5],
            [
                'name' => 'ca'
            ]
        );

        Language::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'de'
            ]
        );

        Language::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'en'
            ]
        );

        Language::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'es'
            ]
        );

        Language::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'fr'
            ]
        );

        Language::updateOrCreate(
            ['id' => 6],
            [
                'name' => 'it'
            ]
        );

        Language::updateOrCreate(
            ['id' => 7],
            [
                'name' => 'zh'
            ]
        );

        Language::updateOrCreate(
            ['id' => 8],
            [
                'name' => 'pt'
            ]
        );

        Language::updateOrCreate(
            ['id' => 9],
            [
                'name' => 'bg'
            ]
        );

        Language::updateOrCreate(
            ['id' => 10],
            [
                'name' => 'ru'
            ]
        );
        
    }
}
