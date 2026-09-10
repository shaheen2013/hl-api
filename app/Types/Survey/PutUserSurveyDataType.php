<?php

namespace App\Types\Survey;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PutUserSurveyDataType extends BaseDataType
{
    /** @var int $brandId */
    protected $brandId;

    /** @var int $userSurveyId */
    protected $userSurveyId;

    /** @var boolean $incidentsReviewed */
    protected $incidentsReviewed;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;
        $this->userSurveyId = $request->user_survey_id;
        $this->incidentsReviewed = $request->get('incidents_reviewed');

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
            'userSurveyId'      => 'numeric|min:1',
            'incidentsReviewed' => 'nullable|boolean',
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
