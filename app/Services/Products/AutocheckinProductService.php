<?php

namespace App\Services\Products;

use App\Events\Autocheckin\AutocheckinUpdated;
use App\Services\BrandProductService;
use App\Exceptions\InvalidRequestException;
use App\Http\Resources\ProductsConfigurations\AutocheckinResource;
use App\Types\AutocheckinDataType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AutocheckinProductService implements HLProduct
{
    const PRODUCT_NAME = 'autocheckin';
    const PAYMENTS_PRODUCT_NAME = 'payments';

    private $productService;
    private $brandProductConfigService;
    private $productConfigService;
    private $brandProductService;

    public function __construct(ProductService $productService, BrandProductConfigService $brandProductConfigService, ProductConfigService $productConfigService, BrandProductService $brandProductService)
    {
        $this->productService = $productService;
        $this->brandProductConfigService = $brandProductConfigService;
        $this->productConfigService = $productConfigService;
        $this->brandProductService = $brandProductService;
    }

    /**
     * @param $brand_id
     * @return AutocheckinResource
     */
    public function getConfiguration($brand_id)
    {
        $product = $this->productService
            ->getByName(self::PRODUCT_NAME);

        $brandProduct = $this->brandProductService
            ->get($brand_id, $product->id);
        
        if (!$brandProduct) {
            $this->brandProductService->activate($brand_id, $product->id, 0);
        }

        $autocheckinConfig = $this->brandProductConfigService->getConfiguration($brand_id, $product->id);

        $paymentsProduct = $this->productService->getByName(self::PAYMENTS_PRODUCT_NAME);
        $brandPayments = $this->brandProductService->get($brand_id, $paymentsProduct->id);

        return new AutocheckinResource((object)$autocheckinConfig, $brandPayments);
    }

    /**
     * @param $brand_id
     * @param Request $request
     * @throws InvalidRequestException
     */
    public function setConfiguration($brand_id, Request $request)
    {
        $autocheckinDataType = new AutocheckinDataType($request);
        if ($autocheckinDataType->isInvalidData()) {
            throw new InvalidRequestException('Invalid request data', [
                'inputs' => $autocheckinDataType->getAttributes(),
                'errors' => $autocheckinDataType->getValidatorMessages()
            ]);
        }

        $inputData = collect($autocheckinDataType->getAttributes())->keyBy(function ($value, $key) {
            return Str::snake($key);
        })->toArray();

        $this->brandProductConfigService->setConfiguration($brand_id, $autocheckinDataType->productId, $inputData);

        $updatedConfig = $this->getConfiguration($brand_id);

        try {
            event(new AutocheckinUpdated([
                "brand" => $brand_id,
                "product" => $autocheckinDataType->productId,
                "config" => $updatedConfig->toArray(null)
            ]));
        } catch (\Exception $e) {
            Log::warning('Failed to send AutocheckinUpdated event', [
                'brand_id' => $brand_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function setDefaultConfiguration($brand_id, $active)
    {
        $autocheckinConfigurations = config('autocheckin.default_configuration');
        foreach ($autocheckinConfigurations as $label => $config) {
            $productConfig = $this->productConfigService->getByLabel($label);
            $brandProduct = $this->brandProductService->get($brand_id, 21);

            $this->brandProductConfigService->firstOrCreate($brandProduct->id, $productConfig->id, json_encode($config));
        }
    }
}
