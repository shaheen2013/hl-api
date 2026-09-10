<?php

use Illuminate\Database\Seeder;
use App\HotelStaffRole;

class GenesisHotelStaffRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        HotelStaffRole::updateOrCreate(
            ['id' => 1],
            [
                'role_es' => 'Administrador de cuenta',
                'role_en' => 'Account Admin'
            ]
        );

        HotelStaffRole::updateOrCreate(
            ['id' => 2],
            [
                'role_es' => 'Administrador de establecimientos',
                'role_en' => 'Brand Admin'
            ]
        );

        HotelStaffRole::updateOrCreate(
            ['id' => 3],
            [
                'role_es' => 'Personal',
                'role_en' => 'Staff'
            ]
        );

        
    }
}
