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
use App\Services\Products\ProductService;
use App\Services\Products\ProductConfigService;
use App\Services\Products\BrandProductConfigService;
use App\Services\BrandService;

class PortalProProductService implements HLProduct
{
    private $productService;
    private $productConfigService;
    private $brandProductConfigService;
    private $brandService;
    private $brandProduct;

    public function __construct(ProductService $productService, ProductConfigService $productConfigService, BrandProductConfigService $brandProductConfigService, BrandService $brandService, BrandProduct $brandProduct)
    {
        $this->productService = $productService;
        $this->productConfigService = $productConfigService;
        $this->brandProductConfigService = $brandProductConfigService;
        $this->brandService = $brandService;
        $this->brandProduct = $brandProduct;
    }

    public function setConfiguration($brand_id, Request $request)
    {
        //
    }

    public function getConfiguration($brand_id)
    {
        //
    }

    public function setDefaultConfiguration($brand_id, $active)
    {
        $brand = $this->brandService->getBrand($brand_id);
        $products = $this->productService->getAll();

        $this->brandProduct->updateOrCreate(
            [
                'brand_id' => $brand_id,
                'product_id' => $products->get('require_room_num')->id
            ],
            [
                'active' => 1
            ]
        );
    }
}
