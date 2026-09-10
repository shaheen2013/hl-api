<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use App\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertChain();
        $this->insertHotel();
    }

    private function insertChain()
    {
        $app_chains = DB::table('cadena')
            ->select('id')
            ->get();

        foreach ($app_chains as $chain) {
            Brand::firstOrCreate([
                'chain_id' => $chain->id
            ], [
                'uuid'          => Uuid::uuid1(),
                'brand_type_id' => 1
            ]);
        }
    }

    private function insertHotel()
    {
        $app_hotels = DB::table('hoteles')
            ->select('hoteles.id', 'brands.id AS parent_id')
            ->leftJoin('cadena_hotel', function ($join) {
                $join->on('hoteles.id', 'cadena_hotel.id_hotel');
            })
            ->leftJoin('brands', function ($join) {
                $join->on('brands.chain_id', 'cadena_hotel.id_cadena');
            })
            ->get();

        foreach ($app_hotels as $hotel) {
            Brand::firstOrCreate([
                'hotel_id' => $hotel->id
            ], [
                'uuid'          => Uuid::uuid1(),
                'parent_id'     => $hotel->parent_id,
                'brand_type_id' => 2
            ]);
        }
    }
}
