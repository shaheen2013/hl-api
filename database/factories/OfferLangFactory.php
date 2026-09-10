<?php

use Faker\Generator as Faker;

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 11/10/18
 * Time: 13:05
 */


$factory->define(App\OfferLang::class, function (Faker $faker) {
    return [
        'id_oferta' => $faker->numberBetween($min = 1, $max = 1000),
        'nombre' => $faker->bs,
        'descripcion' => $faker->text(),
        'condiciones' => $faker->text(),
        'lang' => 'en',
        'lang_ok' => '1',
    ];
}
);