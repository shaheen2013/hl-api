<?php

use App\HotelOfertaLang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UpdateLangOkOffers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
    */
    public function run()
    {
        // Filtramos campos NULL & Vacíos en BB.DD
        HotelOfertaLang::whereNotNull('nombre')
        ->whereNotNull('descripcion')
        ->whereNotNull('condiciones')
        ->where('nombre', '<>', '')
        ->where('descripcion', '<>', '')
        ->where('condiciones', '<>', '')
        ->where('lang_ok', '=', '0')
        ->update(['lang_ok' => 1]);
    }
}
