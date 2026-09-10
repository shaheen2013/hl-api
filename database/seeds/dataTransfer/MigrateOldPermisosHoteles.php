<?php

use App\Product;
use App\ProductConfig;
use App\BrandProduct;
use App\BrandProductConfig;
use App\PermisosHoteles;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class MigrateOldPermisosHoteles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->progressStart(PermisosHoteles::count());
        
        $products = Product::all();
        $displayRoomNumberProductConfig = ProductConfig::where(['label' => 'display_room_number'])->first();
        $birthdayAlarmProductConfig = ProductConfig::where(['label' => 'birthday_alarm'])->first();
        
        PermisosHoteles::chunk(100, function ($hotelPermissions) use ($products, $displayRoomNumberProductConfig, $birthdayAlarmProductConfig) {
            $brandProducts = [];
            $brandProductsConfigs = [];

            foreach ($hotelPermissions as $hotelPermission) {
                $brand = $hotelPermission->hotel->brand ?? null;

                if ($brand) {
                    foreach ($products as $product) {
                        $isActive = data_get($hotelPermission, $product->producto);

                        $brandProduct = BrandProduct::firstOrCreate(
                            [
                                'brand_id' => $brand->id,
                                'product_id' => $product->id
                            ],
                            [
                                "active"     => $isActive
                            ]
                        );   
                        
                        if ($product->producto == 'require_room_num' || $product->producto == 'birthday_emails') {

                            $productConfig = $product->producto == 'require_room_num' ?
                                $displayRoomNumberProductConfig :
                                $birthdayAlarmProductConfig;

                            $isActive = $product->producto == 'require_room_num' ?
                                data_get($hotelPermission, 'display_require_room') :
                                data_get($hotelPermission, 'birthday_alarm');

                            BrandProductConfig::firstOrCreate(
                                [
                                    'brand_product_id'  => $brandProduct->id,
                                    'product_config_id' => $productConfig->id
                                ],
                                [
                                    "value" => $isActive
                                ]
                            );  
                        }
                    }
                }
                $this->command->getOutput()->progressAdvance();
            }
        });

        $this->command->getOutput()->progressFinish();
    }
}