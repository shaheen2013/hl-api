<?php

use Faker\Generator as Faker;

$factory->define(App\Device::class, function (Faker $faker) {
    return [
        'mac_address'   => $faker->macAddress(),
        'device_family' => $faker->word(),
        'device_brand'  => $faker->company(),
        'device_model'  => $faker->word(),
        'created_at'    => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'updated_at'    => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days')
    ];
});
