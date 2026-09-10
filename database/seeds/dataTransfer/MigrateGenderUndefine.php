<?php

use App\User;
use App\UserFacebook;
use Illuminate\Database\Seeder;

class MigrateGenderUndefine extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::where('sexo', '=', 'undefine')
        ->update(['sexo' => '']);

        UserFacebook::where('gender', '=', 'undefined')
        ->update(['gender' => '']);

    }
}
