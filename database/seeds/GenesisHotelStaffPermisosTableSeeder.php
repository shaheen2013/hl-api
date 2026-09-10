<?php

use Illuminate\Database\Seeder;
use App\HotelStaffPermission;

class GenesisHotelStaffPermisosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        HotelStaffPermission::updateOrCreate(
            ['id' => 2],
            [
                'id_staff_role' => 1,
                'id_controller' => 32,
                'default'       => 1
            ]
        );

        HotelStaffPermission::updateOrCreate(
            ['id' => 3],
            [
                'id_staff_role' => 1,
                'id_controller' => 26,
                'default'       => 0
            ]
        );

        HotelStaffPermission::updateOrCreate(
            ['id' => 5],
            [
                'id_staff_role' => 2,
                'id_controller' => 119,
                'default'       => 1
            ]
        );

        HotelStaffPermission::updateOrCreate(
            ['id' => 7],
            [
                'id_staff_role' => 1,
                'id_controller' => 70,
                'default'       => 1
            ]
        );

        HotelStaffPermission::updateOrCreate(
            ['id' => 8],
            [
                'id_staff_role' => 2,
                'id_controller' => 70,
                'default'       => 1
            ]
        );

        HotelStaffPermission::updateOrCreate(
            ['id' => 9],
            [
                'id_staff_role' => 3,
                'id_controller' => 70,
                'default'       => 0
            ]
        );

        HotelStaffPermission::updateOrCreate(
            ['id' => 10],
            [
                'id_staff_role' => 3,
                'id_controller' => 123,
                'default'       => 0
            ]
        );

        HotelStaffPermission::updateOrCreate(
            ['id' => 11],
            [
                'id_staff_role' => 3,
                'id_controller' => 119,
                'default'       => 1
            ]
        );

        HotelStaffPermission::updateOrCreate(
            ['id' => 12],
            [
                'id_staff_role' => 3,
                'id_controller' => 124,
                'default'       => 0
            ]
        );

        HotelStaffPermission::updateOrCreate(
            ['id' => 13],
            [
                'id_staff_role' => 3,
                'id_controller' => 125,
                'default'       => 1
            ]
        );

    }
}
