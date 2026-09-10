<?php

use Faker\Generator as Faker;

$factory->define(App\BrandUrlLanguage::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min = 1, $max = 1000),
        'language_id' => $faker->numberBetween($min = 1, $max = 1000),
        'url' => $faker->url()
    ];
});
