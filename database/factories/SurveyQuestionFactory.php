<?php

use Faker\Generator as Faker;

$factory->define(App\SurveyQuestion::class, function (Faker $faker) {
    return [
        'survey_id' => $faker->numberBetween($min = 1, $max = 1000),
        'question_id' => $faker->numberBetween($min = 1, $max = 1000),
        'active' => $faker->numberBetween($min = 0, $max = 1),
        'required' =>  $faker->numberBetween($min = 0, $max = 1)
    ];
});