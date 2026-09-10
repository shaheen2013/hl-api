<?php

use Faker\Generator as Faker;
use App\DeviceBlacklist;

$factory->define(DeviceBlacklist::class, function (Faker $faker) {
    return [
        'device' => 'Apple Macintosh'
    ];
});
