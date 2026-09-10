<?php

namespace App\Types\Survey;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DeleteUserSurveyType extends BaseDataType
{
    /** @var int $brandId */
    protected $brandId;

    /** @var int $userSurveyId */
    protected $userSurveyId;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->brandId = $request->brand_id;
        $this->userSurveyId = $request->user_survey_id;
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
