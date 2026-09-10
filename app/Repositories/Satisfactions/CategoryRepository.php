<?php

namespace App\Repositories\Satisfactions;

use App\Category;

class CategoryRepository
{
    protected $category;


    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategoryBrand(int $categoryId, int $brandId): Category
    {
        return $this->category->where([
            'id'        => $categoryId,
            'brand_id'  => $brandId
        ])->firstOrFail();
    }

    public function updatePosition(int $brandId, int $categoryId, int $position): void
    {
        $this->category->where('id', $categoryId)->where('brand_id', $brandId)->update(['position' => $position]);
    }

    public function decrementPositions(int $brandId, int $deletedPosition): void
    {
        $this->category->where('brand_id', $brandId)
        ->where('position', '>', $deletedPosition)
        ->decrement('position');
    }
}
