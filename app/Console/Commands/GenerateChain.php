<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/02/2018
 * Time: 17:01
 */

namespace App\Console\Commands;

use App\Cadena;
use App\CadenaGuid;
use App\CadenaHotel;
use App\BrandProduct;
use App\Product;
use Illuminate\Console\Command;

class GenerateChain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelinking:chain {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a Chain with two hotels by default in our DB ( php artisan hotelinking:chain email (yourdesired@email))';

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

        factory(Cadena::class, 1)->create($email ? ['email' => $email] : [])->each(function ($cadena) {
            $cadena->cadenaGuid()->save(factory(CadenaGuid::class)->make());

            $products = [];
            foreach (Product::whereIn('producto', ['LY', 'RF', 'MK'])->get() as $product) {
                $products[] = [
                    'brand_id'   => $cadena->brand->id,
                    'product_id' => $product->id,
                    'active'     => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            BrandProduct::insertIgnore($products);
            $cadena->cadenaHotel()->saveMany(factory(CadenaHotel::class, 2)->create(['id_cadena' => $cadena->id]))->make();
        });
    }
}
