<?php

namespace App\Console\Commands;

use App\BrandProduct;
use App\Hotel;
use App\Product;
use App\HotelGuid;
use App\HotelWifiIntegrations;
use App\LangHotel;
use Illuminate\Console\Command;

class GenerateHotel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:hotel {email?} {integration?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a hotel in our DB ( php artisan hotelinking:hotelSeeder yourdesired@email true||false(for migrations))';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');

        factory(Hotel::class, 1)->create($email ? ['email' => $email] : [])->each(function ($hotel) {
            $products = [];
            $hotel->hotelGuid()->save(factory(HotelGuid::class)->make());

            foreach (Product::all() as $product) {
                $products[] = [
                    'brand_id' => $hotel->brand->id,
                    'product_id' => $product->id,
                    'active' => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            BrandProduct::insertIgnore($products);
            $hotel->langHotel()->save(factory(LangHotel::class)->make());
            if ($this->argument('integration') == true) {
                $hotel->hotelWifiIntegrations()->save(factory(HotelWifiIntegrations::class)->make());
            }
        });
    }
}
