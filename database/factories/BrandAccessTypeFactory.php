<?php

use Faker\Generator as Faker;

$factory->define(App\BrandAccessType::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min = 1, $max = 1000),
        'access_type_id' => $faker->numberBetween($min = 1, $max = 1000),
        'active' => $faker->numberBetween($min = 0, $max = 1),
        'created_at' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'updated_at' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days')
    ];
});