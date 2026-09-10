<?php

use Illuminate\Database\Seeder;
use App\BrandType;

class GenesisBrandTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        BrandType::updateOrCreate(
            ['id' => 1],
            [
                'type' => 'chain',
                'name' => 'Chain'
            ]
        );


        BrandType::updateOrCreate(
            ['id' => 2],
            [
                'type' => 'hotel',
                'name' => 'Hotel'
            ]
        );
    }
}
