<?php

use Faker\Generator as Faker;

$factory->define(App\BrandOfferWifi::class, function (Faker $faker) {
    return [
        'brand_id'   => $faker->numberBetween($min = 1, $max = 1000),
        'offer_id'   => $faker->numberBetween($min = 1, $max = 1000),
        'condition'  => $faker->randomElement(['facebook_login', 'always', 'facebook_share']),
        'offer_type' => $faker->randomElement(['inmediate', 'web']),
        'period'     => $faker->numberBetween($min = 10, $max = 30),
        'valid_from' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = '+30 days'),
        'valid_to'   => $faker->dateTimeBetween($startDate = '-30 days', $endDate = '+30 days'),
        'is_default' => $faker->numberBetween($min = 0, $max = 1)
    ];
});
