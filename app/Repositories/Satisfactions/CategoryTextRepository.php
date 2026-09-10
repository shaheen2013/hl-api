<?php

namespace App\Repositories\Satisfactions;

use App\CategoryText;

class CategoryTextRepository
{
    protected $categoryText;


    public function __construct(CategoryText $categoryText)
    {
        $this->categoryText = $categoryText;
    }

    public function update(int $categoryId, string $lang, string $text): void
    {
        $this->categoryText->updateOrCreate(
            [
                'category_id'   => $categoryId,
                'lang_value'    => $lang
            ],
            [
                'text'          => $text
            ]
        );
    }
}
