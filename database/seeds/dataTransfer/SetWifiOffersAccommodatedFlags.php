<?php

use Illuminate\Database\Seeder;
use App\BrandOfferWifi;

class SetWifiOffersAccommodatedFlags extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BrandOfferWifi::where(["accommodated" => 0])->update(["accommodated" => 1]);
    }
}
