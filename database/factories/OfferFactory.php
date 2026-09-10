<?php

use Faker\Generator as Faker;

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 11/10/2018
 * Time: 12:05
 */

$factory->define(App\Offer::class, function (Faker $faker) {
    return [
        'id_hotel' => $faker->numberBetween($min = 1, $max = 1000),
        'id_cadena' => $faker->numberBetween($min = 1, $max = 1000),
        'id_tipo_oferta' => 'des',
        'id_categoria' => 'des',
        'id_subcategoria' => 'des',
        'adq_ret' => 'ref',
        'inicio' => '2015-07-08',
        'fin' => '0000-00-00',
        'cupo' => 0,
        'adquiridas' => 0,
        'canjeadas' => 0,
        'descuento' => 0,
        'coste' => 0,
        'moneda' => 'EUR',
        'requerimientos' => 0,
        'puntos' => 0,
        'img' => $faker->imageUrl(),
        'estado' => 4,
        'fecha_creacion' => '2015-07-08 08:02:50',
        'fecha_publicada' => '2015-07-08 08:02:50',
        'booking_engine_code' => $faker->text(20),
    ];
}
);