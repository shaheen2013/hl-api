<?php

use Faker\Generator as Faker;

$factory->define(App\UserBrand::class, function(Faker $faker) {
    return [
        'brand_id'      => $faker->numberBetween($min = 1, $max = 1000),
        'user_id'       => $faker->numberBetween($min = 1, $max = 1000),
        'date'          => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'unsubscribed'  => $faker->numberBetween($min = 0, $max = 1)
    ];
});
