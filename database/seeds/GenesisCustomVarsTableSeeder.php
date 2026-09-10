<?php

use Illuminate\Database\Seeder;
use App\CustomVar;

class GenesisCustomVarsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        CustomVar::updateOrCreate(
            ['id' => 1],
            [
                'name'        => '{{brandLegalName}}',
                'description' => 'Brand legal name'
            ]
        );

        CustomVar::updateOrCreate(
            ['id' => 2],
            [
                'name'        => '{{brandLegalAddress}}',
                'description' => 'Brand legal address'
            ]
        );

        CustomVar::updateOrCreate(
            ['id' => 3],
            [
                'name'        => '{{brandNIF}}',
                'description' => 'Brand NIF'
            ]
        );

        CustomVar::updateOrCreate(
            ['id' => 4],
            [
                'name'        => '{{brandLegalEmail}}',
                'description' => 'Brand legal email'
            ]
        );

        CustomVar::updateOrCreate(
            ['id' => 10],
            [
                'name'        => '{{brandMinLegalAge}}',
                'description' => 'Brand legal min age'
            ]
        );

    }
}
