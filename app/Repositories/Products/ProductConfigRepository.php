<?php

namespace App\Repositories\Products;

use App\ProductConfig;
use App\Repositories\Products\ProductConfigRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductConfigRepository implements ProductConfigRepositoryInterface
{
    protected $productConfig;

    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(ProductConfig $productConfig)
    {
        $this->productConfig = $productConfig;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getByLabel(string $name)
    {
        return $this->productConfig->where([
            'label' => $name
        ])->first();
    }

    /**
     * Get all products configs for a productId
     *
     * @param int $productId
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAllByProductId(int $productId): Collection
    {
        $cacheName = "product_configs_by_product_id_{$productId}";
        $cacheTags = ['products', 'product_configs', "product_configs_product_id_{$productId}"];
        $cacheTime = now()->addDays(30);

        $productConfigs = Cache::tags($cacheTags)->get($cacheName);

        if (!$productConfigs) {
            $productConfigs = $this->productConfig
                ->where('product_id', $productId)
                ->get();

            Cache::tags($cacheTags)->put($cacheName, $productConfigs, $cacheTime);
        }

        return $productConfigs;
    }
}
