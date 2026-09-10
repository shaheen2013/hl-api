<?php

use Illuminate\Database\Seeder;

class RunFakeData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CreateFakeChainsSeeder::class);
        $this->call(CreateFakeHotelsSeeder::class);
        $this->call(ConnectionSeeder::class);
    }
}
