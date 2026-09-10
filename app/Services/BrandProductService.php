<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 14/03/2019
 * Time: 10:49
 */

namespace App\Services;

use App\Exceptions\Products\ProductNotFoundException;
use App\Repositories\Brand\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Repositories\Products\BrandProductRepositoryInterface;
use App\Repositories\Products\ProductRepository;
use App\Services\BrandService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class BrandProductService
{
    private $productsMap;
    private $brandProduct;
    private $brandService;
    private $brandRepository;
    private $productRepository;

    public function __construct(BrandProductRepositoryInterface $brandProduct, BrandService $brandService, BrandRepository $brandRepository, ProductRepository $productRepository)
    {
        $this->productsMap = [
            'not_hotel' => 'App\Services\Products\NotHotelProductService',
            'loyalty' => 'App\Services\Products\LoyaltyProductService',
            'customized_satisfaction_surveys' => 'App\Services\Products\CustomizedSurveyProductService',
            'birthday_emails' => 'App\Services\Products\BirthdayEmailsProductService',
            'satisfaction' => 'App\Services\Products\SatisfactionSurveyProductService',
            'review' => 'App\Services\Products\ReviewProductService',
            'portal_pro' => 'App\Services\Products\PortalProProductService',
            'eprivacy_responsible' => 'App\Services\Products\EprivacyResponsibleProductService',
            'require_room_num' => 'App\Services\Products\RequireRoomNumberProductService',
            'widget' => 'App\Services\Products\WidgetProductService',
            'autocheckin' => 'App\Services\Products\AutocheckinProductService',
            'portal' => 'App\Services\Products\PortalProductService',
        ];

        $this->brandProduct = $brandProduct;
        $this->brandService = $brandService;
        $this->brandRepository = $brandRepository;
        $this->productRepository = $productRepository;
    }

    /*********** GENERIC PRODUCT FUNCTIONS *************
     *
     * /**
     * @param $product_name
     * @return \Illuminate\Foundation\Application|mixed|null
     */
    private function getProductService($product_name)
    {
        $productService = null;
        if (Arr::get($this->productsMap, $product_name)) {
            $productService = app(Arr::get($this->productsMap, $product_name));
        }
        return $productService;
    }

    /************ GET PRODUCT CONFIGURATION FUNCTIONS *************
     *
     * @param $brand_id
     * @param $product_id
     * @return null
     */
    public function getConfiguration($brand_id, $product_id)
    {
        $product = $this->productRepository->get($product_id);
        $productConfiguration = null;
        $productService = $this->getProductService($product->name);

        if ($productService) {
            $productConfiguration = $productService->getConfiguration($brand_id);
        }

        return $productConfiguration;
    }

    /************ UPDATE PRODUCT CONFIGURATION FUNCTIONS ************/

    /**
     * @param $brand_id
     * @param $product_id
     * @param $request
     * @return array productConfiguration
     */

    public function updateConfiguration($brand_id, $product_id, Request $request)
    {
        $product = $this->productRepository->get($product_id);
        $productConfiguration = null;
        $productService = $this->getProductService($product->name);
        if ($productService) {
            $productConfiguration = $productService->setConfiguration($brand_id, $request);
        }
        return $productConfiguration;
    }


    public function setJsonConfig($brand_id, $product_id, Request $request)
    {
        $requestData = json_decode($request->getContent(), true);

        $brandProduct = $this->brandProduct->get($brand_id, $product_id);

        if (!$brandProduct) {
            $new_brand_product_row = $this->brandProduct->set([
                'brand_id' => $brand_id,
                'product_id' => $product_id,
                'active' => 1,
                'config' => $requestData
            ]);
        } else {
            $brandProduct->config = $requestData;
            $brandProduct->save();
        }
    }

    public function getJsonConfig($brand_id, $product_id)
    {
        $error = [
            'config' => 'brand product config not found',
            'config_schema' => 'product config schema not found'
        ];
        $brandProduct = $this->brandProduct->get($brand_id, $product_id);
        $config = "";
        if (!$brandProduct) {
            $config = $error['config'];
        } else {
            $config = $brandProduct->config;
            if ($config == null) {
                $config = $error['config'];
            }
        }

        $productSchema = $this->productRepository->get($product_id);
        $config_schema = "";
        if (!$productSchema) {
            $config_schema = $error['config_schema'];
        } else {
            $config_schema = json_decode($productSchema->config_schema, true);
            
            if ($config_schema == null) {
                $config_schema = $error['config_schema'];
            }
        }
    
        $response = json_encode([
            'config' => $config,
            'config_schema' => $config_schema
        ]);
    
        return $response;
    }

    


    /************ UPDATE PRODUCT *************
     *
     * @param $brand_id
     * @param $product_id
     * @param $active
     * @return null
     */

    public function activate($brand_id, $product_id, $active)
    {
        $brand = $this->brandRepository->get($brand_id);
        $hotel = $brand->hotel;
        $product = $this->productRepository->get($product_id);
        if ($hotel && $brand) {
            $this->brandProduct->activate($brand->id, $product_id, $active);
            Cache::tags(["brandInfo_" . $brand_id])->flush();

            $productService = $this->getProductService($product->name);
            $brandService = $this->brandService;

            if ($productService) {
                try {
                    $productService->setDefaultConfiguration($brand_id, $active);
                } catch (\Exception $ex) {
                    Log::error("Error setting default configuration", [
                        "exception"         => $ex,
                        "brand_id"          => $brand_id,
                        "productService"    => $productService
                    ]);

                    throw $ex;
                }
            }

            if ($brandService) {
                $brandService->sendBrandUpdatedEvent($brand_id);
            }
        }
    }

    /**
     * @param $brandID
     * @param $productID
     * @return mixed
     */
    public function get($brandID, $productID)
    {
        return $this->brandProduct->get($brandID, $productID);
    }

    public function getActiveBrands($productId)
    {
        return $this->brandProduct->getActiveBrands($productId);
    }

    /**
     * @param $brandID
     * @return mixed
     */
    public function getAllStatus($brandID)
    {
        return $this->brandProduct->getAllStatus($brandID);
    }

    /**
     * @param array $bulkData
     */
    public function insertIgnore(array $bulkData)
    {
        $this->brandProduct->insertIgnore($bulkData);
    }

    /**
     * @param array $data
     */
    public function set(array $data)
    {
        $this->brandProduct->set($data);
    }

    public function getProductStatus($brandId, $productId)
    {
        try {
            return $this->brandProduct->getProductStatus($brandId, $productId);
        } catch (\Exception $e) {
            throw new ProductNotFoundException("This product doesn't exist", [
                'brand_id' => $brandId,
                'product_id' => $productId
            ]);
        }
    }
}
