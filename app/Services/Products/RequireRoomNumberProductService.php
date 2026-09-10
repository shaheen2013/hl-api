<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 21/06/2019
 * Time: 13:20
 */

namespace App\Services\Products;

use Illuminate\Http\Request;
use App\Services\BrandProductService;
use App\Services\Products\ProductConfigService;
use App\Services\Products\BrandProductConfigService;

class RequireRoomNumberProductService implements HLProduct
{
    private $productConfigService;
    private $brandProductConfigService;
    private $brandProductService;

    public function __construct(ProductConfigService $productConfigService, BrandProductConfigService $brandProductConfigService, BrandProductService $brandProductService)
    {
        $this->productConfigService = $productConfigService;
        $this->brandProductConfigService = $brandProductConfigService;
        $this->brandProductService = $brandProductService;
    }

    public function setConfiguration($brand_id, Request $request)
    {
        $brandProduct = $this->brandProductService->get($brand_id, $request->route('product_id'));

        $displayRoomProductConfig = $this->productConfigService->getByLabel('display_room_number');
        $this->brandProductConfigService->upsert($brandProduct->id, $displayRoomProductConfig->id, $request->value);
    }

    public function getConfiguration($brand_id)
    {
        //
    }

    public function setDefaultConfiguration($brand_id, $active)
    {
        $productConfig = $this->productConfigService->getByLabel('display_room_number');
        $brandProduct = $this->brandProductService->get($brand_id, $productConfig->product_id);

        $this->brandProductConfigService->upsert($brandProduct->id, $productConfig->id, $active);
    }
}
