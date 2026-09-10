<?php

use Faker\Generator as Faker;

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 11/10/2018
 * Time: 12:05
 */

$factory->define(App\OfferGoal::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min = 1, $max = 1000),
        'offer_id' => $faker->numberBetween($min = 1, $max = 1000),
        'product_id' => 11,
        'n_triggers' => $faker->numberBetween($min = 1, $max = 100),
        'days_to_expire' => $faker->numberBetween($min = 1, $max = 100),
        'offer_type' => $faker->randomElement(['web', 'inmediate']),
       
    ];
}
);