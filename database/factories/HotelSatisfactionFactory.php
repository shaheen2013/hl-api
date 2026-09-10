<?php

use Faker\Generator as Faker;

$factory->define(App\HotelSatisfaction::class, function (Faker $faker) {
    return [
        'id_hotel'                   => $faker->numberBetween($min = 1, $max = 1000),
        'diasEnvio'                  => $faker->numberBetween($min = 1, $max = 30),
        'puntMin'                    => $faker->numberBetween($min = 0, $max = 10),
        'review_average_score'       => $faker->numberBetween($min = 0, $max = 10),
        'warning_email'              => $faker->safeEmail,
        'ignoreRating'               => $faker->numberBetween($min = 0, $max = 1),
        'sendThanksMail'             => $faker->numberBetween($min = 0, $max = 1),
        'sendToNonCustomers'         => $faker->numberBetween($min = 0, $max = 1),
        'send_hour'                  => $faker->numberBetween($min = 0, $max = 23),
        'total_followup_email'       => $faker->numberBetween($min = 0, $max = 5),
        'customized_active'          => $faker->numberBetween($min = 0, $max = 1),
        'customized_type'            => $faker->randomElement(['Joined with satisfaction', 'In a later email', 'After PMS checkout date']),
        'customized_send_days'       => $faker->numberBetween($min = 1, $max = 15),
        'customized_send_hours'      => $faker->numberBetween($min = 0, $max = 23),
        'customized_chain_activated' => $faker->numberBetween($min = 0, $max = 1000),
        'childBrandQuestionsID'      => $faker->numberBetween($min = 0, $max = 1000),
        'force_comment'              => $faker->numberBetween($min = 0, $max = 1),
        'customized_comment'         => $faker->numberBetween($min = 0, $max = 1),
        'customized_warning_emails'  => $faker->numberBetween($min = 0, $max = 1)
    ];
});
