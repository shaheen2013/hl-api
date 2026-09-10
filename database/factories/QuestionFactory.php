<?php

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'category_id' => $faker->numberBetween($min = 1, $max = 1000)
    ];
});