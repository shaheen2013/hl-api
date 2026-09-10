<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'email'             => $faker->unique()->safeEmail,
        'nombre'            => substr($faker->name, 0, 100),
        'created'           => $faker->dateTimeBetween($startDate = '-3 years'),
        'location'          => $faker->locale,
        'lang'              => $faker->randomElement(['es', 'en', 'it', 'fr', 'zh']),
        'fecha_nacimiento'  => $faker->date(),
        'sexo'              => $faker->randomElement(['male', 'female']),
        'pais'              => $faker->country,
        'minEstrellas'      => '0',
        'maxEstrellas'      => '0',
        'rango_inf'         => '0',
        'rango_sup'         => '0',
        'notif_hotelinking' => '1',
        'notif_especiales'  => '1',
        'compart_hoteles'   => '0',
        'img'               => '0',
        'tw_followers'      => '0',
        'fb_friends'        => '0',
        'email_result'      => 'deliverable',
        'sendex'            => '0.9',
        'unsubscribed'      => '0',
    ];
});
