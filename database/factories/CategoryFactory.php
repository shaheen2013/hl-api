<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min = 1, $max = 1000),
    ];
});