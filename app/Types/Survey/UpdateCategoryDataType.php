<?php

namespace App\Types\Survey;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UpdateCategoryDataType extends BaseDataType
{
    /** @var int $brandId */
    protected $brandId;

    /** @var int $categoryId */
    protected $categoryId;

    /** @var Array $categoryTexts */
    protected $categoryTexts;

    /** @var object $errors */
    protected $errors;

    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;
        $this->categoryId = $request->category_id;
        $this->categoryTexts = json_decode($request->get('survey_category_text'), true);

        $this->createRules();
    }

    /**
     * @return bool
     */
    public function isInvalidData(): bool
    {
        $data = $this->getAttributes();
        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors();
        }

        return $validator->fails();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function createRules(): void
    {
        $this->rules = [
            'brandId'                       => 'required|numeric|min:1',
            'categoryId'                    => 'required|numeric|min:1',
            'categoryTexts'                 => 'required|array|min:7',
            'categoryTexts.*'               => 'required|array|min:2',
            'categoryTexts.*.lang_value'    => 'required|string|min:2',
            'categoryTexts.*.text'          => 'required|string|min:1'
        ];
    }
}
