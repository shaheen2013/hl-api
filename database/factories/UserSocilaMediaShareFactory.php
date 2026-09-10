<?php

use Faker\Generator as Faker;

$factory->define(\App\SocialMediaShare::class, function (Faker $faker) {
    return [
        'user_brand_id' => $faker->numberBetween($min = 1, $max = 1000),
        'date'          => now(),
        'share_type_id' => $faker->numberBetween($min = 1, $max = 1000)
    ];
});
