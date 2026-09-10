<?php

namespace App\Types\Survey;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ShowSurveyQuestionDataType extends BaseDataType
{
    /** @var int $brandId */
    protected $brandId;

    /** @var String $lang */
    protected $lang;

    /** @var int $active */
    protected $active;

    /** @var int $surveyQuestionId */
    protected $surveyQuestionId;

    /** @var object $errors */
    protected $errors;

    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;

        $this->lang = $request->get('lang');
        $this->active = $request->get('active');
        $this->surveyQuestionId = $request->get('survey_question_id');

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
            'brandId'           => 'required|numeric|min:1',
            'lang'              => 'nullable|string|min:2',
            'active'            => 'nullable|boolean',
            'surveyQuestionId'  => 'nullable|numeric|min:1'
        ];
    }
}
