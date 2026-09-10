<?php

use Faker\Generator as Faker;
/**
 * Created by PhpStorm.
 * User: hl
 * Date: 11/10/18
 * Time: 12:47
 *
 */

$factory->define(App\Connection::class, function (Faker $faker) {
    return [
        'brand_id'                  => $faker->numberBetween($min = 1, $max = 1000),
        'visit_id'                  => $faker->numberBetween($min = 1, $max = 1000),
        'device_id'                 => $faker->numberBetween($min = 1, $max = 1000),
        'access_type_id'            => $faker->numberBetween($min = 1, $max = 2),
        'access_code'               => $faker->word(),
        'is_client'                 => $faker->numberBetween($min = 0, $max = 1),
        'headers'                   => $faker->text(),
        'browser'                   => $faker->numberBetween($min = 1, $max = 2),
        'browser_version'           => $faker->numberBetween($min = 1, $max = 2),
        'browser_lang'              => $faker->randomElement(['es', 'en', 'it', 'fr', 'zh']),
        'operating_system_version'  => $faker->numberBetween($min = 1, $max = 100),
        'created_at'                => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days'),
        'updated_at'                => $faker->dateTimeBetween($startDate = '-10 month', $endDate = '-30 days')
    ];
});
