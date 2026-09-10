<?php

use Faker\Generator as Faker;
/**
 * Created by PhpStorm.
 * User: hl
 * Date: 11/10/18
 * Time: 12:47
 *
 */

$factory->define(App\Brand::class, function (Faker $faker) {
    return [
        'uuid' => $faker->numberBetween($min =1, $max = 1000),
        'hotel_id' => $faker->numberBetween($min = 1, $max = 1000),
        'chain_id' => $faker->numberBetween($min = 1, $max = 1000),
        'parent_id' => null,
        'brand_type_id' => $faker->numberBetween($min = 1, $max = 2),
        'is_verified' => 1,
        'background_color' => null
    ];
});
