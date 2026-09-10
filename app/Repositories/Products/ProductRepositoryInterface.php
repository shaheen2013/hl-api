<?php

namespace App\Repositories\Products;

interface ProductRepositoryInterface
{
    public function get($product_id);

    /**
     * @param string $name
     * @return mixed
    */
    public function getByName(string $name);

    /**
     * @return mixed
     */
    public function getAll();
}
