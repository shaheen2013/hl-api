<?php

use Faker\Generator as Faker;

$factory->define(App\PermisosHoteles::class, function (Faker $faker) {
    return [
        'id_hotel'                          => $faker->numberBetween($min = 0, $max = 9999),
        'LY'                                => $faker->numberBetween($min = 0, $max = 1),
        'RF'                                => $faker->numberBetween($min = 0, $max = 1),
        'MK'                                => $faker->numberBetween($min = 0, $max = 1),
        'review'                            => $faker->numberBetween($min = 0, $max = 1),
        'satisfaction'                      => $faker->numberBetween($min = 0, $max = 1),
        'wifi_offers'                       => $faker->numberBetween($min = 0, $max = 1),
        'birthday_emails'                   => $faker->numberBetween($min = 0, $max = 1),
        'pushtech'                          => $faker->numberBetween($min = 0, $max = 1),
        'require_room_num'                  => $faker->numberBetween($min = 0, $max = 1),
        'display_require_room'              => $faker->numberBetween($min = 0, $max = 1),
        'wifi_days'                         => $faker->numberBetween($min = 0, $max = 9999),
        'user_enrichment'                   => $faker->numberBetween($min = 0, $max = 1),
        'birthday_alarm'                    => $faker->numberBetween($min = 0, $max = 1),
        'loyalty'                           => $faker->numberBetween($min = 0, $max = 1),
        'eprivacy_responsible'              => $faker->numberBetween($min = 0, $max = 1),
        'followup_mail'                     => $faker->numberBetween($min = 0, $max = 1),
        'not_hotel'                         => $faker->numberBetween($min = 0, $max = 1),
        'portal_pro'                        => $faker->numberBetween($min = 0, $max = 1),
        'datamatch'                         => $faker->numberBetween($min = 0, $max = 1),
        'widget'                            => $faker->numberBetween($min = 0, $max = 1),
        'customized_satisfaction_surveys'   => $faker->numberBetween($min = 0, $max = 1),
        'trans_comments'                    => $faker->numberBetween($min = 0, $max = 1),
        'ip_binding'                        => $faker->numberBetween($min = 0, $max = 1),
    ];
});
