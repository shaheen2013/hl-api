<?php

namespace App\Types\Shared;

use App\Types\Shared\BaseDataType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaginationDataType extends BaseDataType
{
    protected $pageItems;
    protected $page;
    protected $dateFrom;
    protected $dateTo;
    protected $maxPageItemsPaginate = 500;
    protected $validatorMessages = [];
    protected $rules;

    public function __construct(Request $request)
    {
        $this->pageItems = $request->input('page_items');
        $this->page = $request->input('page');
        $this->dateFrom = $request->input('date_from');
        $this->dateTo = $request->input('date_to');
    }

    /**
     * @inheritDoc
     */
    public function isInvalidData(): bool
    {
        $data = $this->getAttributes();
        $validator = Validator::make($data, $this->rules, [
            'between' => 'The :attribute value :input is not between :min - :max.',
            'in'      => 'The :attribute must be one of the following types: :values',
        ]);

        $this->validatorMessages = $validator->errors()->all();

        return $validator->fails();
    }

    /**
     *
     */
    protected function createRules(): void
    {
        // Add initial rules
        $this->rules = [
            'pageItems' => 'nullable|integer|between:1,' . $this->maxPageItemsPaginate,
            'page'      => 'nullable|integer',
            'dateFrom'  => 'nullable|date_format:Y-m-d',
            'dateTo'    => 'nullable|date_format:Y-m-d',
        ];
    }

    /**
     * @return array
     */
    public function getValidatorMessages()
    {
        return $this->validatorMessages;
    }

    /**
     * @return array
     */
    public function getAttributesForUrl(): array
    {
        // Get all attributes on snake_case format to construct url
        $attributes = collect($this->getAttributes())
            ->except(['maxPageItemsPaginate', 'validatorMessages', 'rules'])
            ->filter(function ($value) {
                return !is_null($value);
            })
            ->mapWithKeys(function ($value, $att) {
                return [
                    Str::snake($att) => $value
                ];
            })->toArray();

        return $attributes;
    }
}
