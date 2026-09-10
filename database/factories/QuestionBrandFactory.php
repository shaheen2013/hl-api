<?php

use Faker\Generator as Faker;

$factory->define(App\QuestionBrand::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min = 1, $max = 1000),
        'question_id' => $faker->numberBetween($min = 1, $max = 1000),
        'required' => $faker->numberBetween($min = 0, $max = 1),
        'type' => $faker->randomElement(['Rating', 'Multiresponse', 'Open Question']),
        'allow_multiple_responses' => $faker->numberBetween($min = 0, $max = 1),
    ];
});