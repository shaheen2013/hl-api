<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 15/03/2019
 * Time: 13:20
 */

namespace App\Services\Products;

use App\Brand;
use App\BrandProduct;
use Illuminate\Http\Request;
use App\Services\Products\ProductConfigService;
use App\Services\Products\BrandProductConfigService;
use App\Services\BrandProductService;

class BirthdayEmailsProductService implements HLProduct
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
        $birthdayAlarmProductConfig = $this->productConfigService->getByLabel('birthday_alarm');

        if ($brandProduct) {
            $this->brandProductConfigService->upsert($brandProduct->id, $birthdayAlarmProductConfig->id, $request->birthday_alarm);
        }
    }

    public function getConfiguration($brand_id)
    {
        //
    }

    public function setDefaultConfiguration($brand_id, $active)
    {

        $brand = Brand::findOrFail($brand_id);
        $hotel = $brand->hotel;
        if ($active && $hotel) {
            if (!$hotel->birthdayAlertEmails && $hotel->hotelSatisfaction) {
                $hotel->birthdayAlertEmails = $hotel->hotelSatisfaction->warning_email;
                $hotel->save();
            }
        }
    }
}
