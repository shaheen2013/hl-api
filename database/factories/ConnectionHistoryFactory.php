<?php

use Faker\Generator as Faker;

$factory->define(App\ConnectionHistory::class, function (Faker $faker) {
    return [
        'id_hotel'          => $faker->numberBetween($min = 1, $max = 1000),
        'id_user'           => $faker->numberBetween($min = 1, $max = 1000),
        'first_login'       => $faker->dateTimeBetween($startDate = '-60 days', $endDate = '-30 days'),
        'last_login'        => $faker->dateTimeBetween($startDate = '-30 days'),
        'mac_address'       => $faker->macAddress,
        'times_login'       => 1,
    ];
});
