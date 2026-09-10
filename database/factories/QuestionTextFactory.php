<?php

use Faker\Generator as Faker;

$factory->define(App\QuestionText::class, function (Faker $faker) {
    return [
        'question_id' => $faker->numberBetween($min = 1, $max = 1000),
        'lang_value' => 'es',
        'text' => $faker->text()
    ];
});