<?php

namespace App\Services\Products;

use App\Exceptions\ProductConfig\ProductConfigNotFound;
use App\Repositories\Products\BrandProductConfigRepositoryInterface;
use App\Services\BrandProductService;
use Illuminate\Database\Eloquent\Collection;

class BrandProductConfigService
{
    private $brandProductConfigRepository;
    private $productConfigService;
    private $brandProductService;

    public function __construct(
        BrandProductConfigRepositoryInterface $brandProductConfigRepository,
        ProductConfigService $productConfigService,
        BrandProductService $brandProductService
    ) {
        $this->brandProductConfigRepository = $brandProductConfigRepository;
        $this->productConfigService = $productConfigService;
        $this->brandProductService = $brandProductService;
    }

    /**
     * @param int $brandProductID
     * @param int $productConfigID
     * @return mixed
     */
    public function get(int $brandProductID, int $productConfigID)
    {
        return $this->brandProductConfigRepository->get($brandProductID, $productConfigID);
    }

    /**
     * @param int $brandProductID
     * @param int $productConfigID
     * @param $value
     * @return mixed
     */
    public function upsert(int $brandProductID, int $productConfigID, $value)
    {
        return $this->brandProductConfigRepository->upsert($brandProductID, $productConfigID, $value);
    }

    /**
     * @param int $brandProductID
     * @param int $productConfigID
     * @param $value
     * @return mixed
     */
    public function firstOrCreate(int $brandProductID, int $productConfigID, $value)
    {
        return $this->brandProductConfigRepository->firstOrCreate($brandProductID, $productConfigID, $value);
    }

    /**
     * @param int $brandProductId
     * @return Collection
     */
    public function getAllByBrandProductId(int $brandProductId): Collection
    {
        return $this->brandProductConfigRepository->getAllByBrandProductId($brandProductId);
    }

    /**
     * @param int $brandId
     * @param int $productId
     * @return array
     */
    public function getConfiguration(int $brandId, int $productId): array
    {
        $brandProduct = $this->brandProductService
            ->get($brandId, $productId);

        if (!$brandProduct) {
            $detail = [
                'brand_id'   => $brandId,
                'product_id' => $productId
            ];

            throw new ProductConfigNotFound('Brand product config not found', $detail, 404);
        }

        $productConfigs = $this->productConfigService
            ->getAllByProductId($productId)
            ->keyBy('id')
            ->toArray();

        $brandProductConfigs = $this->getAllByBrandProductId($brandProduct->id);

        $config = [
            'active' => (bool)$brandProduct->active
        ];

        foreach ($brandProductConfigs as $brandProductConfig) {
            $productConfigId = $brandProductConfig['product_config_id'];
            $label = $productConfigs[$productConfigId]['label'];
            $type = $productConfigs[$productConfigId]['type'];
            $value = $brandProductConfig['value'];

            if ($type == 'json') {
                $config[$label] = json_decode($value, true);
                continue;
            }

            if ($type == 'boolean') {
                // Convert string to boolean. 'false' will be false and 'true' will be true
                $config[$label] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                continue;
            }

            // Allow empty values for close_time_limit_checkin to avoid close checkin
            if (!(!$value && $label === "close_time_limit_checkin")) {
                settype($value, $type);
            }

            $config[$label] = $value;
        }

        return $config;
    }

    /**
     * @param int $brandId
     * @param int $productId
     * @param array $inputData
     */
    public function setConfiguration(int $brandId, int $productId, array $inputData): void
    {
        $brandProduct = $this->brandProductService
            ->get($brandId, $productId);

        if (!$brandProduct) {
            $detail = [
                'brand_id'   => $brandId,
                'product_id' => $productId
            ];

            throw new ProductConfigNotFound('Brand product config not found', $detail, 404);
        }

        $productConfigs = $this->productConfigService
            ->getAllByProductId($productId);

        foreach ($productConfigs as $productConfig) {
            $productConfigId = $productConfig['id'];
            $label = $productConfig['label'];
            $type = $productConfig['type'];
            $value = $inputData[$label] ?? null;

            // Allow empty values for close_time_limit_checkin to avoid close checkin
            if (is_null($value) && $label === "close_time_limit_checkin") {
                $value = "";
            }

            if (is_null($value)) {
                continue;
            }

            if ($type == 'json') {
                $value = json_encode($value);
            }

            $this->upsert($brandProduct->id, $productConfigId, $value);
        }
    }
}
