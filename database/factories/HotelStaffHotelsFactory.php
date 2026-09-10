<?php

use Faker\Generator as Faker;

$factory->define(App\HotelStaffHotels::class, function (Faker $faker) {
    return [
        'hotel_id'              => $faker->numberBetween($min = 1, $max = 1000),
        'hotel_staff_id'        => $faker->numberBetween($min = 1, $max = 1000),
    ];
});
