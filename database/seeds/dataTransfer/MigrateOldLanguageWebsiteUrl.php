<?php

use App\BrandUrlLanguage;
use App\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateOldLanguageWebsiteUrl extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hotels = DB::table('hoteles')
                    ->select(
                        'brands.id as id',
                        'websiteReserva as en',
                        'websiteReserva_es as es',
                        'websiteReserva_de as de',
                        'websiteReserva_fr as fr'
                    )
                    ->join('brands', 'hoteles.id', '=', 'brands.hotel_id')
                    ->get();

        $chain = DB::table('cadena')
                    ->select(
                        'brands.id as id',
                        'websiteReserva as en',
                        'websiteReserva_es as es',
                        'websiteReserva_de as de',
                        'websiteReserva_fr as fr'
                    )
                    ->join('brands', 'cadena.id', '=', 'brands.chain_id')
                    ->get();
        
                       
        $brands = $hotels->concat($chain);
        
        $langs = DB::table('languages')
        ->select('id', 'name')
        ->whereIn('name', ['en', 'es', 'de', 'fr'])
        ->get();
          
        $brandUrlLanguageList = [];
        $now = now();

        foreach ($brands as $brand) {
            foreach ($langs as $lang) {
                array_push($brandUrlLanguageList, ([
                    'brand_id' => $brand->id, 
                    'language_id'=> $lang->id, 
                    'url'=> $brand->{$lang->name},
                    'created_at' => $now,
                    'updated_at' => $now
                ]));
            }
        }

        BrandUrlLanguage::insert($brandUrlLanguageList);  
    }
}
