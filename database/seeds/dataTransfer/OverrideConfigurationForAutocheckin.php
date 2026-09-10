<?php

use App\BrandProduct;
use App\ProductConfig;
use App\BrandProductConfig;
use App\Services\Products\AutocheckinProductService;
use Illuminate\Database\Seeder;

class OverrideConfigurationForAutocheckin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(AutocheckinProductService $autocheckinProductService)
    {
        $autocheckinConfigurations = config('autocheckin.default_configuration');
        $brandProducts = BrandProduct::where(['product_id' => 21])->get();

        foreach ($brandProducts as $brandProduct) {
            $autocheckinProductService->setDefaultConfiguration($brandProduct->brand_id, 1);
        }
    }
}
