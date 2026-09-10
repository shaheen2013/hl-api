<?php

use Illuminate\Database\Seeder;
use App\BrandOfferWifi;
use App\Hotel;

class PopulateBrandOfferWifi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (DB::table('hotel_oferta_wifi')->get() as $hotelOfferWifi) {
            $hotel = Hotel::find($hotelOfferWifi->id_hotel);
            if (!$hotel) {
                continue;
            }

            $hotel->load('brand');

            BrandOfferWifi::firstOrCreate([
                'brand_id'   => $hotel->brand->id,
                'offer_id'   => $hotelOfferWifi->id_oferta,
                'condition'  => ($hotelOfferWifi->condicion == 'siempre' ? 'always' : $hotelOfferWifi->condicion),
                'offer_type' => $hotelOfferWifi->tipo_oferta,
                'period'     => $hotelOfferWifi->dias,
                'is_default' => true
            ]);
        }
    }
}
