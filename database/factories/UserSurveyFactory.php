<?php

use Faker\Generator as Faker;

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 11/10/18
 * Time: 13:22
 */


$factory->define(App\UserSurvey::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min = 1, $max = 1000),
        'survey_id' => $faker->numberBetween($min = 1, $max = 1000),
        'user_id' => $faker->numberBetween($min = 1, $max = 1000),
        'access_code' => $faker->text(),
        'assisted' => $faker->numberBetween($min = 0, $max = 1),
        'assisted_staff_id' => null,
        'incidents_reviewed' => $faker->numberBetween($min = 0, $max = 1),
        'review_sent' => $faker->numberBetween($min = 0, $max = 1),
        'send_date' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'customized_send_date' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'created_at' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'updated_at' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'user_satisfaction_id' => $faker->numberBetween($min = 1, $max = 1000),
        'assisted_at' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days')
    ];
});
