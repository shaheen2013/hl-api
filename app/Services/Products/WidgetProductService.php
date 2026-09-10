<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 21/06/2019
 * Time: 13:20
 */

namespace App\Services\Products;

use App\Brand;
use App\BrandProduct;
use App\BrandProductConfig;
use Illuminate\Http\Request;
use App\Services\Products\ProductConfigService;
use App\Services\Products\BrandProductConfigService;

class WidgetProductService implements HLProduct
{
    private $productConfigService;
    private $brandProductConfigService;

    public function __construct(ProductConfigService $productConfigService, BrandProductConfigService $brandProductConfigService)
    {
        $this->productConfigService = $productConfigService;
        $this->brandProductConfigService = $brandProductConfigService;
    }

    public function setConfiguration($brand_id, Request $request)
    {
        $brandProduct = BrandProduct::where(
            [
                'brand_id'   => $brand_id,
                'product_id' => $request->route('product_id')
            ]
        )->first();

        $widgetByChainProductConfig = $this->productConfigService->getByLabel('byChain');
        $this->brandProductConfigService->upsert($brandProduct->id, $widgetByChainProductConfig->id, $request->value);
    }

    public function getConfiguration($brand_id)
    {
        $brandProduct = BrandProduct::where(
            [
                'brand_id'   => $brand_id,
                'product_id' => 14
            ]
        )->first();

        $productConfig = $this->productConfigService->getByLabel('byChain');

        if ($brandProduct) {
            return $this->brandProductConfigService->get($brandProduct->id, $productConfig->id);
        }
    }

    public function setDefaultConfiguration($brand_id, $active)
    {
        //
    }
}
