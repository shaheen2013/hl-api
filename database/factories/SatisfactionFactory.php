<?php

use Faker\Generator as Faker;

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 11/10/18
 * Time: 13:22
 */


$factory->define(App\Satisfaction::class, function (Faker $faker, array $attributes = []) {
    return [
        'id_usuario'            => $faker->numberBetween($min = 1, $max = 1000),
        'id_hotel'              => $faker->numberBetween($min = 1, $max = 1000),
        'id_cadena'             => $faker->numberBetween($min = 1, $max = 1000),
        'puntuacion'            => $faker->numberBetween($min = 0, $max = 10),
        'comentario'            => $faker->text(),
        'send_date'             => $faker->dateTimeBetween($startDate = '-3 years', $endDate = '-3 days'),
        'customized_send_date'  => $faker->dateTimeBetween($startDate = '-3 years', $endDate = '-3 days'),
        'fecha_creado'          => $faker->dateTimeBetween($startDate = '-3 years', $endDate = '-3 days'),
        'fecha_update'          => $faker->dateTimeBetween($startDate = '-3 years', $endDate = '-3 days'),
        'done'                  => $faker->numberBetween($min = 0, $max = 1),
        'review_send'           => $faker->numberBetween($min = 0, $max = 1),
        'has_been_seen'         => $faker->numberBetween($min = 0, $max = 1),
        'favorite'              => $faker->numberBetween($min = 0, $max = 1),
        'who_has_been_seen'     => $faker->numberBetween($min = -1, $max = 1000),
        'id_room'               => $faker->word,
    ];
});