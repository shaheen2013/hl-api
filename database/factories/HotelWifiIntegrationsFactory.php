<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/02/2018
 * Time: 12:39
 */
use Faker\Generator as Faker;

$factory->define(App\HotelWifiIntegrations::class, function (Faker $faker) {
    return [
        'wifi_id'=>9
    ];
});