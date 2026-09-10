<?php

use Faker\Generator as Faker;
/**
 * Created by PhpStorm.
 * User: hl
 * Date: 11/10/18
 * Time: 12:47
 *
 */

$factory->define(App\BrandProtocol::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min = 1, $max = 1000),
        'service' => $faker->randomElement(['emails', 'portal']),
        'treatment' => $faker->randomElement(['formal', 'informal'])
    ];
});