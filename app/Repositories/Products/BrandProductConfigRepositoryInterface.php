<?php

namespace App\Repositories\Products;

use Illuminate\Database\Eloquent\Collection;

interface BrandProductConfigRepositoryInterface
{
    /**
     * @param int $brandProductID
     * @param int $productConfigID
     * @return mixed
     */
    public function get(int $brandProductID, int $productConfigID);

    /**
     * @param int $brandProductID
     * @param int $productConfigID
     * @param $value
     * @return mixed
     */
    public function upsert(int $brandProductID, int $productConfigID, $value);

    /**
     * @param int $brandProductID
     * @param int $productConfigID
     * @param $value
     * @return mixed
     */
    public function firstOrCreate(int $brandProductID, int $productConfigID, $value);

    /**
     * @param int $brandProductId
     * @return Collection
     */
    public function getAllByBrandProductId(int $brandProductId): Collection;
}
