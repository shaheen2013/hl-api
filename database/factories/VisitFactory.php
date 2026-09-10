<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
/**
 * Created by PhpStorm.
 * User: hl
 * Date: 11/10/18
 * Time: 12:47
 *
 */

$factory->define(App\Visit::class, function (Faker $faker) {
    // Generate checkIn and checkOut to get always a checkIn < checkOut
    $checkIn = Carbon::now()->subMonths(rand(5,10));
    $checkOut = Carbon::parse($checkIn)->addDays(rand(5,20));
    return [
        'user_brand_id' => $faker->numberBetween($min =1, $max = 1000),
        'brand_id'      => $faker->numberBetween($min =1, $max = 1000),
        'user_id'       => $faker->numberBetween($min =1, $max = 1000),
        'check_in'      => $checkIn,
        'check_out'     => $checkOut,
        'is_client'     => 1
    ];
});

$factory->state(App\Visit::class, 'withReservation', function (Faker $faker) {
    $channels = [
        "EasyJet", "Booking", "TUI", "OpenTours", "WEB",
        "Pago Directo", "AIR Transit", "Touroperador",
        "NT Incoming", "Expedia", "Other", "Roiback",
        "Call Center", "AllTours"
    ];
    
    $resId = Carbon::now()->year . '_' . $faker->numberBetween($min=100, $max = 999) . "_" . $faker->numberBetween($min =1, $max = 9);

    return [
        "reservation" => json_encode([
            "city" => $faker->city(),
            "gender" => $faker->randomElement(['male', 'female']),
            "pms_id" => $resId . '_1',
            "res_id" => $resId,
            "address" => $faker->streetAddress(),
            "birthday" => $faker->date(),
            "brand_id" => $faker->numberBetween($min =1, $max = 1000),
            "check_in" => $faker->date(),
            "hotel_id" => $faker->numberBetween($min =1, $max = 1000),
            "pax_type" => $faker->randomElement(['AD', 'CH', 'JR']),
            "province" => $faker->state(),
            "res_data" => null,
            "res_date" => $faker->date(),
            "check_out" => $faker->date(),
            "last_name" => $faker->lastName(),
            "res_board" => "HD",
            "telephone" => $faker->phoneNumber(),
            "first_name" => $faker->firstName(),
            "res_adults" => $faker->numberBetween($min=1, $max = 3),
            "res_agency" => $faker->word(),
            "res_amount" => $faker->numberBetween($min=100, $max = 1000),
            "res_babies" => $faker->numberBetween($min=0, $max = 1),
            "res_extras" => null,
            "res_nights" => $faker->numberBetween($min=1, $max = 10),
            "document_id" => null,
            "nationality" => null,
            "postal_code" => $faker->postcode(),
            "res_channel" =>  $faker->randomElement($channels),
            "res_company" => null,
            "res_juniors" => $faker->numberBetween($min=0, $max = 1),
            "res_seniors" => null,
            "res_children" => $faker->numberBetween($min=0, $max = 2),
            "res_comments" => null,
            "res_contract" => null,
            "res_currency" => $faker->randomElement(['EUR', 'GBP','USD', 'CAD', 'DKK', 'SEK', 'CHF']),
            "birth_country" => $faker->country(),
            "res_room_type" => $faker->randomElement(["SI", "DO", "SU", "PR"]),
            "res_room_number" => $faker->numberBetween($min=1, $max = 1000),
            "res_intermediary" => null,
            "residence_country" => $faker->country(),
        ])
    ];
});

$factory->afterMakingState(App\Visit::class, 'withReservation', function ($visit, $faker) {
    $reservation = json_decode($visit->reservation, true);

    // Set the parent's check_in and check_out date to reservations json
    $reservation["check_in"] = $visit->check_in;
    $reservation["check_out"] = $visit->check_out;
    $reservation["res_nights"] = $visit->check_in->diff($visit->check_out)->days;
    $reservation["res_date"] = Carbon::parse($visit->check_in)->subDays(rand(5,30));
    $reservation["brand_id"] = $visit->userBrand->brand_id;
    $reservation["hotel_id"] = $visit->userBrand->brand->hotel_id;

    $visit->reservation = json_encode($reservation);
});