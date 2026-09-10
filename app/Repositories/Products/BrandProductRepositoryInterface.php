<?php

namespace App\Repositories\Products;

interface BrandProductRepositoryInterface
{
    /**
     * @param int $brand_id
     * @param int $product_id
     * @return mixed
    */
    public function get(int $brand_id, int $product_id);

    /**
     * @param int $brand_id
     * @param int $product_id
     * @param int $active
     * @return mixed
    */

    /**
     * @param int $productID
     * @return mixed
     */
    public function getActiveBrands(int $productId);

    public function activate(int $brand_id, int $product_id, int $active);

    /**
     * @param $brandID
     * @return mixed
     */
    public function getAllStatus(int $brandID);

    /**
     * @param array $bulkData
     * @return mixed
     */
    public function insertIgnore(array $bulkData);

    /**
     * @param array $data
     * @return mixed
     */
    public function set(array $data);

    /**
     * @param int $brandId
     * @param int $productId
     * @return mixed
     */
    public function getProductStatus(int $brandId, int $productId);
}
