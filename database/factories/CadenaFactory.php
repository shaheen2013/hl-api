<?php

use Faker\Generator as Faker;

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/02/2018
 * Time: 17:05
 */

$factory->define(App\Cadena::class, function (Faker $faker) {
    $email = $faker->unique()->safeEmail;
    return [
        'nombre'             => substr($faker->company, 0, 30),
        'logo'               => $faker->imageUrl,
        'descripcion'        => $faker->text(),
        'email'              => $email,
        'email_envio'        => $email,
        'password'           => '3d816a4200a01ed808182fbc55e2ed62e2506da9',
        'email_contacto'     => $email,
        'telefono_contacto'  => '123',
        'booking_engine'     => $faker->text(20),
        'stay_time'          => '7',
        'loyalty_min_visits' => '2'
    ];
});
