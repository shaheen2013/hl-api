<?php

use Faker\Generator as Faker;

$factory->define(App\Survey::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min = 1, $max = 1000),
        'name' => $faker->text(),
        'created_at' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'updated_at' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days')
    ];
});