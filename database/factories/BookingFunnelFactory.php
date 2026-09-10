<?php

use Faker\Generator as Faker;

$factory->define(\App\BookingFunnel::class, function (Faker $faker) {
    return [
        'user_id'        => $faker->numberBetween($min = 1, $max = 1000),
        'brand_id'       => $faker->numberBetween($min = 1, $max = 1000),
        'date'           => now(),
        'booking_action' => $faker->randomElement(['home', 'availability', 'checkout', 'booking'])
    ];
});
