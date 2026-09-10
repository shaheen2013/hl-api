<?php

use Faker\Generator as Faker;

$factory->define(App\HotelStaff::class, function (Faker $faker) {
    return [
        'nombre'            => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'id_role'           => 3,
        'activo'            => 1,
        'deleted'           => $faker->numberBetween($min = 0, $max = 1),
        'password'          => '3d816a4200a01ed808182fbc55e2ed62e2506da9',
        'fecha_creado'      => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
    ];
});
