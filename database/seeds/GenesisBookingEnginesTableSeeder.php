<?php

use Illuminate\Database\Seeder;
use App\BookingEngine;

class GenesisBookingEnginesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        BookingEngine::updateOrCreate(
            ['id' => 1],
            [
                'name'             => 'roiback',
                'url'              => null,
                'getParam'         => 'cp',
                'gtm_container_id' => 8957105,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null,
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 2],
            [
                'name'             => 'guestcentric',
                'url'              => 'https://secure.guestcentric.net/api/bg/book.php',
                'getParam'         => 'pc',
                'gtm_container_id' => 9444108,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 3],
            [
                'name'             => 'travelclick',
                'url'              => 'https://bookings.ihotelier.com/',
                'getParam'         => 'identifier',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 4],
            [
                'name'             => 'synxis',
                'url'              => 'https://gc.synxis.com/rez.aspx',
                'getParam'         => 'promo',
                'gtm_container_id' => 11771873,
                'gtm_workspace_id' => 23,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 5],
            [
                'name'             => 'hotelia',
                'url'              => 'https://hotelia.hotelinking.com',
                'getParam'         => 'coupon',
                'gtm_container_id' => 8975308,
                'gtm_workspace_id' => 32,
                'gtm_variable_map' => '{"hl_beTransactionCode":"hl-transaction","hl_environment":"production","hotelinking_url":"https://app.hotelinking.com","hl_amount":"hl-amount","hl_uid":"kwJykJrf5FM2uN8qNmsDbEdSbawSLvMG","hl_promoCode":"hl-promocode","hl_category":"h","hl_currency":"hl-currency","hl_validate":"1","hl_API":"apiv1-1","hl_SCRIPTS":"https://devscripts.hotelinking.com","hl_checkin":"hl-checkin","hl_checkout":"hl-checkout","hl_hotelID":"{{hl_bookingPrefix}}_{{hl_bookingHotelID}}","hl_version":"2.13.0","hl_bookingHotelID":"hotel-id","hl_bookingPrefix":"HL"}',
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 7],
            [
                'name'             => 'cabau',
                'url'              => null,
                'getParam'         => 'promo',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null,]
        );

        BookingEngine::updateOrCreate(
            ['id' => 8],
            [
                'name'             => 'bluesea',
                'url'              => 'http://www.blueseahotels.com/',
                'getParam'         => 'pchl',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 9],
            [
                'name'             => 'markSite',
                'url'              => null,
                'getParam'         => 'promoCode',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 10],
            [
                'name'             => 'bookingCore',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 10888870,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 11],
            [
                'name'             => 'viva',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 12],
            [
                'name'             => 'prinsotel',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 13],
            [
                'name'             => 'hoteltools',
                'url'              => null,
                'getParam'         => '_promo',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 14],
            [
                'name'             => 'hotetec',
                'url'              => null,
                'getParam'         => 'promoCode',
                'gtm_container_id' => 9749319,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 15],
            [
                'name'             => 'dingus',
                'url'              => null,
                'getParam'         => 'promo_code',
                'gtm_container_id' => 10255855,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 16],
            [
                'name'             => 'Idiso',
                'url'              => null,
                'getParam'         => 'promoCode',
                'gtm_container_id' => 8959886,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 17],
            [
                'name'             => 'zafiro',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 18],
            [
                'name'             => 'witbooking',
                'url'              => null,
                'getParam'         => 'prom',
                'gtm_container_id' => 9384685,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 19],
            [
                'name'             => 'm-hotel',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 21],
            [
                'name'             => 'adora',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 0,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 22],
            [
                'name'             => 'vertical_booking',
                'url'              => null,
                'getParam'         => 'generic_codice',
                'gtm_container_id' => 8583514,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 23],
            [
                'name'             => 'mirai',
                'url'              => null,
                'getParam'         => 'clientCode',
                'gtm_container_id' => 8368404,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 24],
            [
                'name'             => 'ecommerce_hotels',
                'url'              => null,
                'getParam'         => 'hl-promocode',
                'gtm_container_id' => 9101499,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 25],
            [
                'name'             => 'neobooking',
                'url'              => null,
                'getParam'         => 'promo',
                'gtm_container_id' => 8992822,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 26],
            [
                'name'             => 'aga',
                'url'              => null,
                'getParam'         => 'Request.PromotionalC',
                'gtm_container_id' => 9263317,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 27],
            [
                'name'             => 'paraty_tech',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 8974782,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 28],
            [
                'name'             => 'simple_booking',
                'url'              => '',
                'getParam'         => 'promocode',
                'gtm_container_id' => 11035939,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => ''
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 29],
            [
                'name'             => 'homotec',
                'url'              => '',
                'getParam'         => 'promocode',
                'gtm_container_id' => 9633167,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => ''
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 30],
            [
                'name'             => 'centrotel',
                'url'              => '',
                'getParam'         => 'promocode',
                'gtm_container_id' => 9355545,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => ''
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 31],
            [
                'name'             => 'thinkin',
                'url'              => '',
                'getParam'         => 'promocode',
                'gtm_container_id' => 9993631,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => ''
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 32],
            [
                'name'             => 'eturistic',
                'url'              => '',
                'getParam'         => 'promocode',
                'gtm_container_id' => 11313773,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => ''
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 33],
            [
                'name'             => 'dfsistemes',
                'url'              => '',
                'getParam'         => 'promocode',
                'gtm_container_id' => 11313924,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => ''
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 34],
            [
                'name'             => 'whin',
                'url'              => '',
                'getParam'         => 'promocode',
                'gtm_container_id' => 11314055,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => ''
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 35],
            [
                'name'             => 'xotels-busyroom',
                'url'              => '',
                'getParam'         => 'promocode',
                'gtm_container_id' => 10178949,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => ''
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 36],
            [
                'name'             => 'apsl',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 11979953,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 37],
            [
                'name'             => 'apsl-zafiro',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 12730565,
                'gtm_workspace_id' => 0,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 38],
            [
                'name'             => 'bookassist',
                'url'              => null,
                'getParam'         => 'promo_code',
                'gtm_container_id' => 9302448,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 39],
            [
                'name'             => 'guest-pro',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 46614763,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );

        BookingEngine::updateOrCreate(
            ['id' => 40],
            [
                'name'             => 'staah',
                'url'              => null,
                'getParam'         => 'promocode',
                'gtm_container_id' => 34007044,
                'gtm_workspace_id' => null,
                'gtm_variable_map' => null
            ]
        );
    }
}
