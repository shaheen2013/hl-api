<?php

namespace App\Repositories\Products;

use Illuminate\Database\Eloquent\Collection;

interface ProductConfigRepositoryInterface
{
    /**
     * @param string $name
     * @return mixed
    */
    public function getByLabel(string $name);

    /**
     * @param int $productId
     * @return Collection
     */
    public function getAllByProductId(int $productId): Collection;
}
