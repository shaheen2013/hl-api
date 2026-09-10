<?php

use Faker\Generator as Faker;

$factory->define(App\UserHotel::class, function(Faker $faker) {
    return [
        'id_usuario'     => $faker->numberBetween($min = 1, $max = 1000),
        'id_hotel'       => $faker->numberBetween($min = 1, $max = 1000),
        'id_cadena'      => $faker->numberBetween($min = 1, $max = 1000),
        'fecha'          => '2015-07-08 08:02:50',
        'total_noches'   => 0,
        'gasto_total'    => 0,
        'total_checkins' => 0,
        'follow'         => 0,
        'user_hotel_id'  => null,
        'customer'       => 1,
        'guid'           => $faker->uuid,
        'unsubscribed'   => 0
    ];
});
