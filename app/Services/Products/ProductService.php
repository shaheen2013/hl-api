<?php

namespace App\Services\Products;

use App\Repositories\Products\ProductRepositoryInterface;

class ProductService
{
    private $product;

    public function __construct(ProductRepositoryInterface $product)
    {
        $this->product = $product;
    }

    /**
     * @param string
     * @return mixed
     */
    public function getByName($name)
    {
        return $this->product->getByName($name);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->product->getAll();
    }
}
