<?php

use Faker\Generator as Faker;

$factory->define(App\CategoryText::class, function (Faker $faker) {
    return [
        'category_id' => $faker->numberBetween($min = 1, $max = 1000),
        'lang_value' => 'es',
        'text' => $faker->word()
    ];
});