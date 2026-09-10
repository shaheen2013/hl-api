<?php

use Faker\Generator as Faker;

$factory->define(App\SatisfactionAnswer::class, function (Faker $faker) {
    return [
        'user_satisfaction_id' => $faker->numberBetween($min = 1, $max = 1000),
        'survey_question_id' => $faker->numberBetween($min = 1, $max = 1000),
        'brand_id' => $faker->numberBetween($min = 1, $max = 1000),
        'answer' => $faker->numberBetween($min = 1, $max = 10),
    ];
});