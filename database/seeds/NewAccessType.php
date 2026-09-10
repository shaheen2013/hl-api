<?php

use Illuminate\Database\Seeder;

class NewAccessType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formAccessType = App\AccessType::updateOrCreate(
            ['id' => 1],
            ['name' => 'Form']
        );

        App\BrandAccessType::firstOrCreate(
            [
                'brand_id'          => null,
                'access_type_id'    => $formAccessType->id,
                'active'            => 1
            ]
        );
        
        $facebookAccessType = App\AccessType::updateOrCreate(
            ['id' => 2],
            ['name' => 'Facebook']
        );

        App\BrandAccessType::firstOrCreate(
            [
                'brand_id'          => null,
                'access_type_id'    => $facebookAccessType->id,
                'active'            => 1
            ]
        );

        $googleAccessType = App\AccessType::updateOrCreate(
            ['id' => 3],
            ['name' => 'Google']
        );

        App\BrandAccessType::firstOrCreate(
            [
                'brand_id'          => null,
                'access_type_id'    => $googleAccessType->id,
                'active'            => 0
            ]
        );
    }
}
