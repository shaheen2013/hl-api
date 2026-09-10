<?php

use Faker\Generator as Faker;

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 19/01/2018
 * Time: 10:43
 */
$factory->define(App\HotelReview::class, function (Faker $faker) {
    return [
        'id_hotel'      => $faker->numberBetween($min = 1, $max = 1000),
        'diasEnvio'     => $faker->numberBetween($min = 1, $max = 30),
        'ignoreRating'  => $faker->numberBetween($min = 0, $max = 1),
        'send_type'     => $faker->randomElement(['after_wifi', 'after_satisfaction', 'after_check_out']),

    ];
});
