<?php

namespace App\Services\Products;

use App\Repositories\Products\ProductConfigRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductConfigService
{
    private $productConfigRepository;

    public function __construct(ProductConfigRepositoryInterface $productConfigRepository)
    {
        $this->productConfigRepository = $productConfigRepository;
    }

    /**
     * @param string
     * @return mixed
     */
    public function getByLabel(string $name)
    {
        return $this->productConfigRepository->getByLabel($name);
    }

    /**
     * @param int $productId
     * @return Collection
     */
    public function getAllByProductId(int $productId): Collection
    {
        return $this->productConfigRepository->getAllByProductId($productId);
    }
}
