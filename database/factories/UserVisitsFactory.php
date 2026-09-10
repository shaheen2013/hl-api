<?php

use Faker\Generator as Faker;

use App\UserVisits;
use App\User;
use App\Hotel;
use App\Cadena;
use App\CadenaGuid;
use App\CadenaHotel;
use App\Product;
use App\BrandProduct;
use Illuminate\Support\Carbon;

$factory->define(UserVisits::class, function (Faker $faker) {
    return [
      'user_id' => function(){
        return factory(User::class)->create()->id;
      },
      'hotel_id' => function(){
        return factory(Hotel::class)->create()->id;
      },
      'last_login'=> Carbon::now(),
      'num_visits'=> 1
    ];
});



$factory->state(UserVisits::class, 'chain', function($faker){
  $chain = factory(Cadena::class, 1)->create()->each(function($chain){
            $chain->cadenaGuid()->save(factory(CadenaGuid::class)->make());

            $products = [];
            foreach (Product::whereIn('producto', ['LY', 'RF', 'MK'])->get() as $product) {
                $products[] = [
                    'brand_id'   => $chain->brand->id,
                    'product_id' => $product->id,
                    'active'     => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            BrandProduct::insertIgnore($products);
            $chain->cadenaHotel()->saveMany(factory(CadenaHotel::class,2)->create(['id_cadena'=>$chain->id]))->make();
          });

    return [
      'user_id' => function(){
        return factory(User::class)->create()->id;
      },
      'hotel_id' => $chain->first()->cadenaHotel->first()->id_hotel,
      'chain_id'=> $chain->first()->id,
      'last_login'=> Carbon::now(),
      'num_visits'=> 1
    ];

});
