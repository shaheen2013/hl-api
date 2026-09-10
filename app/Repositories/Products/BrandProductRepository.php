<?php

namespace App\Repositories\Products;

use App\BrandProduct;
use App\Exceptions\Products\ProductNotFoundException;

class BrandProductRepository implements BrandProductRepositoryInterface
{
    /**
     * @var BrandsProducts
     */
    protected $brandProduct;

    /**
     * BrandProductRepository constructor.
     * @param BrandProduct $brandProduct
     */
    public function __construct(BrandProduct $brandProduct)
    {
        $this->brandProduct = $brandProduct;
    }

    /**
     * @param int $brandID
     * @param int $productID
     * @return mixed
     */
    public function get(int $brandID, int $productID)
    {
        return $this->brandProduct->where([
            'brand_id' => $brandID,
            'product_id' => $productID
        ])->first();
    }

    /**
     * @param int $productID
     * @return mixed
     */
    public function getActiveBrands(int $productId)
    {
        return $this->brandProduct->where([
            'product_id'    => $productId,
            'active'        => 1
        ])->get();
    }

    /**
     * @param int $brandID
     * @param int $productID
     * @param int $active
     * @return mixed|void
     */
    public function activate(int $brandID, int $productID, int $active)
    {
        $this->brandProduct->updateOrCreate(
            [
                'brand_id' => $brandID,
                'product_id' => $productID
            ],
            [
                'active' => $active
            ]
        );
    }

    /**
     * @param $brandID
     * @return mixed
     */
    public function getAllStatus(int $brandID)
    {
        $allProducts = app(ProductRepository::class)->getAll();
        
        $existingBrandProducts = $this->brandProduct
            ->where('brand_id', $brandID)
            ->get()
            ->keyBy('product_id');

        $products = collect();
        
        foreach ($allProducts as $product) {
            if ($existingBrandProducts->has($product->id)) {
                $products->push($existingBrandProducts->get($product->id));
            } else {
                // Create default brand product structure for products without relationship
                $products->push($this->brandProduct->make([
                    'brand_id' => $brandID,
                    'product_id' => $product->id,
                    'active' => false,
                    'name' => $product->name
                ]));
            }
        }

        return $products;
    }

    /**
     * @param $brandId
     * @return mixed
     * @throws ProductNotFoundException
     */
    public function getProductStatus(int $brandId, int $productId)
    {
        $product = $this->brandProduct
            ->where('brand_id', $brandId)
            ->where('product_id', $productId)
            ->first();

        if (!$product) {
            // Check if product exists in products table
            $productExists = app(ProductRepository::class)->get($productId);
            
            if (!$productExists) {
                throw new ProductNotFoundException("Product not found", [
                    'brand_id' => $brandId,
                    'product_id' => $productId
                ]);
            }

            // Return an unsaved model instance with default values
            return $this->brandProduct->make([
                'product_id' => $productExists->id,
                'active' => false,
                'name' => $productExists->name
            ]);
        }

        return $product;
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
        $this->brandProduct->firstOrCreate($data);
    }

    public function updateProductStatus(int $brandId)
    {
        // Update brand products
        $this->brandProduct->where('brand_id', $brandId)->where('product_id', '!=', 24)->update(['active' => 0]);
    }
}
