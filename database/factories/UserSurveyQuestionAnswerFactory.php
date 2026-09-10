<?php

use Faker\Generator as Faker;

$factory->define(App\UserSurveyQuestionAnswer::class, function (Faker $faker) {
    return [
        'survey_question_id' => $faker->numberBetween($min = 1, $max = 1000),
        'user_survey_id' => $faker->numberBetween($min = 1, $max = 1000),
        'answer' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 1),
        'comment' => $faker->text(),
        'favorite' => $faker->numberBetween($min = 0, $max = 1),
    ];
});
