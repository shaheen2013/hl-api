<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 18/03/2019
 * Time: 10:07
 */

namespace App\Services\Products;

use App\Brand;
use App\HotelReview;
use App\Http\Resources\ProductsConfigurations\ReviewProductConfiguration;
use App\Services\BrandProductService;
use App\Services\Products\ProductService;
use App\Repositories\Products\BrandProductRepositoryInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReviewProductService implements HLProduct
{

    const SATISFACTION_PRODUCT_NAME = 'satisfaction';
    const REVIEW_PRODUCT_NAME = 'review';
    const AFTER_CHECK_OUT = 'after_check_out';

    private $brandProductService;
    private $productService;
    private $brandProductRepository;

    public function __construct(BrandProductService $brandProductService, ProductService $productService, BrandProductRepositoryInterface $brandProductRepository)
    {
        $this->brandProductService = $brandProductService;
        $this->productService = $productService;
        $this->brandProductRepository = $brandProductRepository;
    }

    public function setConfiguration($brandId, Request $request)
    {
        $brand = Brand::findOrFail($brandId);
        HotelReview::updateOrCreate(
            ['id_hotel' => $brand->hotel->id],
            [
                'diasEnvio'     => $request->get('send_after_days'),
                'ignoreRating'  => $request->get('ignore_rating'),
                'send_type'     => $request->get('send_type'),
            ]
        );
    }

    public function getConfiguration($brandId)
    {
        $brand = Brand::findOrFail($brandId);
        return new ReviewProductConfiguration($brand);
    }

    public function setDefaultConfiguration($brand_id, $active)
    {
        $brand = Brand::findOrFail($brand_id);
        $hotel = $brand->hotel;
        HotelReview::firstOrCreate(
            ['id_hotel' => $hotel->id],
            ['diasEnvio' => 15]
        );

        $satisfactionProduct = $this->productService->getByName(self::SATISFACTION_PRODUCT_NAME);
        $reviewProduct = $this->productService->getByName(self::REVIEW_PRODUCT_NAME);
        $satisfactionBrandProduct = $this->brandProductService->get($brand_id, $satisfactionProduct->id);
        $reviewBrandProduct = $this->brandProductService->get($brand_id, $reviewProduct->id);

        if ($active && data_get($satisfactionBrandProduct, 'active') && data_get($reviewBrandProduct, 'active')) {
            $this->brandProductRepository->activate($brand->id, $reviewProduct->id, 0);
        }
    }

    public function getSendDate($configuration, $visit)
    {
        $targetDate = $configuration->send_type === self::AFTER_CHECK_OUT && data_get($visit, "check_out")
            ? Carbon::parse($visit->check_out)
            : Carbon::now();
    
        return $targetDate->addDays($configuration->send_after_days)->toIso8601ZuluString();
    }
}
