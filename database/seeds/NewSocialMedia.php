<?php

use Illuminate\Database\Seeder;

class NewSocialMedia extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\SocialMedia::firstOrCreate(
            ['name' => 'Facebook']
        );

        App\SocialMedia::firstOrCreate(
            ['name' => 'WeChat']
        );

        App\SocialMedia::firstOrCreate(
            ['name' => 'Twitter']
        );
    }
}
