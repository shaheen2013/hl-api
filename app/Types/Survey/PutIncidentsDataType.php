<?php

namespace App\Types\Survey;

use App\Types\Shared\BaseDataType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PutIncidentsDataType extends BaseDataType
{
    /** @var int $userSurveyId */
    protected $userSurveyId;

    /** @var int ?$hotel_staff_id */
    protected $hotelStaffId;

    /** @var string $incident_text */
    protected $incidentText;

    /**
     * @param Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->userSurveyId = $request->user_survey_id;

        $this->hotelStaffId = $request->get('hotel_staff_id');

        $this->incidentText = $request->get('incident_text');

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
            'userSurveyId'  => 'numeric|min:1',
            'hotelStaffId'  => 'nullable|numeric|min:1',
            'searchText'    => 'nullable|string',
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
