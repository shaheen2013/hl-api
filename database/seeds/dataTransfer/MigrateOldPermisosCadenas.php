<?php

use App\Product;
use App\BrandProduct;
use App\PermisosCadenas;
use Illuminate\Database\Seeder;

class MigrateOldPermisosCadenas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->progressStart(PermisosCadenas::count());
        $products = Product::all();

        PermisosCadenas::chunk(100, function ($chainPermissions) use ($products) {
            $brandProducts = [];

            foreach ($chainPermissions as $chainPermission) {
                $brand = $chainPermission->cadenaGuid->brand ?? null;

                if ($brand) {
                    foreach ($products as $product) {
                        $isActive = data_get($chainPermission, $product->producto);

                        array_push($brandProducts, [
                            "brand_id"   => $brand->id,
                            "product_id" => $product->id,
                            "active"     => $isActive,
                            "created_at" => now(),
                            "updated_at" => now()
                        ]);
                    }
                }

                $this->command->getOutput()->progressAdvance();

            }

            BrandProduct::insert($brandProducts);

        });

        $this->command->getOutput()->progressFinish();
    }
}
