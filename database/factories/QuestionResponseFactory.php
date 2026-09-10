<?php

use Faker\Generator as Faker;

$factory->define(App\QuestionResponse::class, function (Faker $faker) {
    return [
        'question_id' => $faker->numberBetween($min = 1, $max = 1000),
        'allow_comment' => $faker->numberBetween($min = 0, $max = 1),
    ];
});