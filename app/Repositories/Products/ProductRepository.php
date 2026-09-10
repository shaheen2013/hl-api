<?php

namespace App\Repositories\Products;

use App\Product;
use App\Repositories\Products\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    protected $product;

    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function get($product_id)
    {
        $tagName = ["product_" . $product_id];
        $cacheName = "product_" . $product_id;

        $product = Cache::tags($tagName)->get($cacheName);
        if (!$product) {
            $product = $this->product->findOrFail($product_id);
            Cache::tags($tagName)->put($cacheName, $product, now()->addDays(10));
        }
        return $product;
    }

    public function getByName(string $name)
    {
        return $this->product->where([
            'producto' => $name
        ])->first();
    }

    /**
     * Get list of products
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection
    {
        $cacheName = "products_list";
        $cacheTags = ['products'];
        $cacheTime = now()->addDays(30);

        $products = Cache::tags($cacheTags)->get($cacheName);

        if (!$products) {
            $products = $this->product->all()->keyBy('name');

            Cache::tags($cacheTags)->put($cacheName, $products, $cacheTime);
        }

        return $products;
    }
}
