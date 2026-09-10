<?php
/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/02/2018
 * Time: 17:50
 */

use App\HotelGuid;
use App\LangHotel;
use App\BrandProduct;
use App\Product;

$factory->define(App\CadenaHotel::class, function () {
    $hotel = factory(App\Hotel::class)->create();
    factory(HotelGuid::class)->create(['id_hotel' => $hotel->id]);
    factory(LangHotel::class)->create(['id_hotel' => $hotel->id]);
    $cadenaHotel = ['id_hotel' => $hotel->id];

    return $cadenaHotel;
});
