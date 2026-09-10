<?php

use Faker\Generator as Faker;

$factory->define(App\LoyaltyConfig::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min = 0, $max = 1000),
        'summary_active' => $faker->numberBetween($min = 0, $max = 1),
        'summary_send_days' => $faker->numberBetween($min = 0, $max = 30),
        'summary_send_hours' => $faker->numberBetween($min = 0, $max = 23),
    ];
});
