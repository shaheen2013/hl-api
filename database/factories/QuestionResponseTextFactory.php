<?php

use Faker\Generator as Faker;

$factory->define(App\QuestionResponseText::class, function (Faker $faker) {
    return [
        'question_response_id' => $faker->numberBetween($min = 1, $max = 1000),
        'lang_value' => 'es',
        'text' => $faker->word()
    ];
});