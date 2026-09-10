<?php

use App\HotelStaff;
use Illuminate\Database\Seeder;
class PopulateOldStaffRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HotelStaff::where(["id_role" => 1])->orWhere(["id_role" => 2])->update(["id_role" => 3]);
    }
}
