<?php

namespace App\Repositories\Products;

use App\BrandProductConfig;
use App\Repositories\Products\BrandProductConfigRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BrandProductConfigRepository implements BrandProductConfigRepositoryInterface
{
    protected $brandProductConfig;

    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(BrandProductConfig $brandProductConfig)
    {
        $this->brandProductConfig = $brandProductConfig;
    }

    /**
     * @param int $brandProductID
     * @param int $productConfigID
     * @return mixed
     */
    public function get(int $brandProductID, int $productConfigID)
    {
        $cacheName = 'brand_product_config' . $brandProductID . '_product_config' . $productConfigID;
        $cacheTags = ['brand_product_config' . $brandProductID];
        $cacheTime = Carbon::now()->addDays(7);

        $brandProductConfig = Cache::tags($cacheTags)->get($cacheName);

        if (!$brandProductConfig) {
            $brandProductConfig = $this->brandProductConfig->where(
                [
                    'brand_product_id' => $brandProductID,
                    'product_config_id' => $productConfigID
                ]
            )->first();

            Cache::tags($cacheTags)->put($cacheName, $brandProductConfig, $cacheTime);
        }

        return $brandProductConfig;
    }

    /**
     * @param int $brandProductID
     * @param int $productConfigID
     * @param $value
     * @return mixed
     */
    public function upsert(int $brandProductID, int $productConfigID, $value)
    {
        Cache::tags(['brand_product_config' . $brandProductID])->flush();

        return $this->brandProductConfig->updateOrCreate(
            [
                'brand_product_id' => $brandProductID,
                'product_config_id' => $productConfigID
            ],
            [
                'value' => $value
            ]
        );
    }

    /**
     * @param int $brandProductID
     * @param int $productConfigID
     * @param $value
     * @return mixed
     */
    public function firstOrCreate(int $brandProductID, int $productConfigID, $value)
    {
        Cache::tags(['brand_product_config' . $brandProductID])->flush();

        return $this->brandProductConfig->firstOrCreate(
            [
                'brand_product_id' => $brandProductID,
                'product_config_id' => $productConfigID
            ],
            [
                'value' => $value
            ]
        );
    }

    /**
     * @param int $brandProductId
     * @return Collection
     */
    public function getAllByBrandProductId(int $brandProductId): Collection
    {
        $cacheName = 'all_brand_product_config' . $brandProductId;
        $cacheTags = ['brand_product_config' . $brandProductId];
        $cacheTime = Carbon::now()->addDays(7);

        $brandProductConfig = Cache::tags($cacheTags)->get($cacheName);

        if (!$brandProductConfig) {
            $brandProductConfig =  $this->brandProductConfig
                ->where('brand_product_id', $brandProductId)
                ->get();

            Cache::tags($cacheTags)->put($cacheName, $brandProductConfig, $cacheTime);
        }

        return $brandProductConfig;
    }
}
