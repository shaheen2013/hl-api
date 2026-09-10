<?php

namespace App\Types\Survey;

use App\Types\Shared\PaginationDataType;
use Illuminate\Http\Request;

class SurveyAnswersDataType extends PaginationDataType
{
    protected $language;
    protected $score;
    protected $category;
    protected $favorite;
    protected $brands;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->maxPageItemsPaginate = config('services.users.paginate.max', 500);
        $this->language = $request->input('language');
        $this->score = $request->input('score');
        $this->category = $request->input('category') ?? 'N/A';
        $this->favorite = $request->input('favorite');
        $this->brands = $request->input('brands') ? array_map('trim', explode(',', $request->input('brands'))) : [];

        $this->createRules();
    }

    protected function createRules(): void
    {
        // Add rules for paginate
        parent::createRules();

        // Add complementary rules
        $this->rules = array_merge(
            $this->rules,
            [
                'language'  => 'nullable|string|size:2',
                'brands'    => 'nullable|array',
                'brands.*'  => 'nullable|integer|min:1',
                'score'     => 'nullable|integer|between:0,10',
                'favorite'  => 'nullable|integer|in:0,1',
                'category'  => 'nullable|string',
            ]
        );
    }

    /**
     * @return array
     */
    public function getAttributesForUrl(): array
    {
        // Get parent convertion to snake_case
        $attributes = parent::getAttributesForUrl();

        // Implode brands ids list comma separeted
        $attributes['brands'] = implode(',', $attributes['brands']);

        return $attributes;
    }
}
