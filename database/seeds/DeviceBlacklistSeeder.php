<?php

use Illuminate\Database\Seeder;
use App\DeviceBlacklist;

class DeviceBlacklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeviceBlacklist::updateOrCreate(
            ['id' => 1],
            [
                'device' => 'PlayStation'
            ]
        );

        DeviceBlacklist::updateOrCreate(
            ['id' => 2],
            [
                'device' => 'PS3'
            ]
        );

        DeviceBlacklist::updateOrCreate(
            ['id' => 3],
            [
                'device' => 'PS4'
            ]
        );
    }
}
