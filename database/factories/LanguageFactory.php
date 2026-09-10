<?php

use Faker\Generator as Faker;

$factory->define(App\Language::class, function (Faker $faker) {
    return[
        'name' => substr($faker->name, 0, 2)
    ];
});
