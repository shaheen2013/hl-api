<?php

namespace App\Types\Surveys;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class StoreSurveyQuestionDataType extends BaseDataType
{
    /** @var int $surveyCategoryId */
    protected $surveyCategoryId;

    /** @var boolean $required */
    protected $required;

    /** @var boolean $allowComment */
    protected $allowComment;

    /** @var boolean $multipleSelection */
    protected $multipleSelection;

    /** @var string $type */
    protected $type;

    /** @var string $surveyQuestionsText */
    protected $surveyQuestionsText;

    /** @var string $surveyAnswers */
    protected $surveyAnswers;

    private $validatorMessages = [];
    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->surveyCategoryId = $request->get('survey_category_id');

        $this->required = $request->get('required');

        $this->type = $request->get('type');

        $this->allowComment = $request->get('allow_comment') === 'on';

        $this->multipleSelection = $request->get('multiple_selection') === 'on';

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
        $this->validatorMessages = $validator->errors()->all();

        return $validator->fails();
    }

    protected function createRules(): void
    {
        $this->rules = [
            'surveyCategoryId'      => 'required|numeric|min:1',
            'surveyQuestionsText'   => 'required|array',
            'surveyAnswers'         => 'nullable|array',
            'required'              => 'required|bool',
            'type'                  => 'required|string|in:Multiresponse,Rating,Open Question',
            'allowComment'          => 'bool',
            'multipleSelection'     => 'bool'
        ];
    }

    /**
     * @return array
     */
    public function getValidatorMessages()
    {
        return $this->validatorMessages;
    }
}
