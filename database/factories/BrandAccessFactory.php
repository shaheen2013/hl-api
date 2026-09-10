<?php

use Faker\Generator as Faker;

$factory->define(App\BrandAccess::class, function (Faker $faker) {
    return [
        'brand_id' => $faker->numberBetween($min =1, $max = 1000),
        'codes' => json_encode([
            "room" => [
                "codes" => randomArray($faker),
            ],
            "guest" => [
                "codes" => randomArray($faker),
            ],
            "premium" => [
                "codes" => randomArray($faker),
                "byParent" => $faker->boolean
            ]
        ]),
        'created_at' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'updated_at' => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days')
    ];
});

if (!function_exists('randomArray')) {
    function randomArray($faker)
    {
        $array = [];

        for ($i=0; $i < $faker->numberBetween(1, 50); $i++) 
        {
            array_push($array, "$faker->name");
        }

        return $array;
    }
}   