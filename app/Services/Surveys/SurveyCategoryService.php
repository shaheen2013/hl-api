<?php

namespace App\Services\Surveys;

use App\Types\Survey\UpdateCategoryDataType;
use App\Repositories\Satisfactions\CategoryTextRepository;
use App\Repositories\Satisfactions\CategoryRepository;

class SurveyCategoryService
{
    protected $categoryRepository;
    protected $categoryTextRepository;

    public function __construct(CategoryRepository $categoryRepository, CategoryTextRepository $categoryTextRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryTextRepository = $categoryTextRepository;
    }

    public function updateCategoryTexts(UpdateCategoryDataType $data): void
    {
        $category = $this->categoryRepository->getCategoryBrand($data->categoryId, $data->brandId);

        if ($category) {
            foreach ($data->categoryTexts as $categoryText) {
                $this->categoryTextRepository->update($category->id, data_get($categoryText, 'lang_value'), data_get($categoryText, 'text'));
            }
        }
    }

    public function updateOrder($brandId, $categoryOrder) : void
    {
        foreach ($categoryOrder as $index => $categoryId) {
            $this->categoryRepository->updatePosition($brandId, $categoryId, $index + 1);
        }
    }

    public function decrementPositions($brandId, $deletedPosition) : void
    {
        $this->categoryRepository->decrementPositions($brandId, $deletedPosition);
    }
}
