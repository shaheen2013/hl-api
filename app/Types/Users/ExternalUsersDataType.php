<?php

namespace App\Types\Users;

use App\Types\Shared\PaginationDataType;
use Illuminate\Http\Request;

class ExternalUsersDataType extends PaginationDataType
{
    protected $language;
    protected $brands;
    protected $unsubscribed;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->maxPageItemsPaginate = config('services.users.paginate.max', 500);
        $this->language = $request->input('language');
        $this->brands = $request->input('brands') ? array_map('trim', explode(',', $request->input('brands'))) : [];
        $this->unsubscribed = $request->input('unsubscribed');

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
                'unsubscribed' => 'nullable|boolean'
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
