<?php

namespace App\Services\Products;

use App\Services\BrandProductService;
use Illuminate\Http\Request;

class PortalProductService implements HLProduct
{
    const PRODUCT_NAME = 'portal';

    private $productConfigService;
    private $productService;
    private $brandProductConfigService;
    private $brandProductService;

    public function __construct(ProductService $productService, BrandProductConfigService $brandProductConfigService, BrandProductService $brandProductService, ProductConfigService $productConfigService)
    {
        $this->productService = $productService;
        $this->brandProductConfigService = $brandProductConfigService;
        $this->brandProductService = $brandProductService;
        $this->productConfigService = $productConfigService;
    }

    /**
     * @param $brand_id
     */
    public function getConfiguration($brand_id)
    {
        $product = $this->productService
            ->getByName(self::PRODUCT_NAME);

        try {
            $generalConfiguration = $this->brandProductConfigService->getConfiguration($brand_id, $product->id);
        } catch (\Exception $e) {
            $generalConfiguration = config('general_configuration.default_configuration');
        }

        return $generalConfiguration;
    }

    /**
     * @param $brand_id
     * @param Request $request
     */
    public function setConfiguration($brand_id, Request $request)
    {
        $productId = $request->route('product_id');
        $this->brandProductService->activate($brand_id, $productId, 1);
        $brandProduct = $this->brandProductService->get($brand_id, $productId);
        $hideUnsubscribedClients = $this->productConfigService->getByLabel('hide_unsubscribed_clients');

        if ($brandProduct) {
            $this->brandProductConfigService->upsert($brandProduct->id, $hideUnsubscribedClients->id, $request->hide_unsubscribed_clients);
        }
    }

    public function setDefaultConfiguration($brand_id, $active)
    {
        //
    }
}
