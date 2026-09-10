<?php

namespace App\Types\Survey;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UpdateQuestionDataType extends BaseDataType
{
    /** @var int $brandId */
    protected $brandId;

    /** @var int $categoryId */
    protected $questionId;

    /** @var int $active */
    protected $active;

    /** @var int $required */
    protected $required;

    /** @var Array $surveyQuestionsText */
    protected $surveyQuestionsText;

    /** @var Array $surveyAnswers */
    protected $surveyAnswers;

    /** @var object $errors */
    protected $errors;

    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;
        $this->questionId = $request->question_id;

        $this->required = $request->get('required');
        $this->active = $request->get('active');

        $this->surveyQuestionsText = $request->get('survey_questions_text') ?
            json_decode($request->get('survey_questions_text'), true) :
            null;

        $this->surveyAnswers = $request->get('survey_answers') ?
            json_decode($request->get('survey_answers'), true) :
            null;

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
            'brandId'                                   => 'required|numeric|min:1',
            'questionId'                                => 'required|numeric|min:1',
            'active'                                    => 'nullable|boolean',
            'required'                                  => 'nullable|boolean',
            'surveyQuestionsText'                       => 'nullable|array|min:7',
            'surveyQuestionsText.*'                     => 'required|array|min:2',
            'surveyQuestionsText.*.lang_value'          => 'required|string|min:2',
            'surveyQuestionsText.*.text'                => 'required|string|min:1',
            'surveyAnswers'                             => 'nullable|array|min:1',
            'surveyAnswers.*'                           => 'required|array|min:7',
            'surveyAnswers.*.*'                         => 'required|array|min:2',
            'surveyAnswers.*.*.question_response_id'    => 'required|numeric|min:1',
            'surveyAnswers.*.*.lang_value'              => 'required|string|min:2',
            'surveyAnswers.*.*.text'                    => 'required|string|min:1'
        ];
    }
}
