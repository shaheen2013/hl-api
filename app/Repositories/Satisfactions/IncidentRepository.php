<?php

namespace App\Repositories\Satisfactions;

use App\UserSurveyIncident;
use App\Types\Survey\PutIncidentsDataType;
use Carbon\Carbon;

class IncidentRepository implements IncidentRepositoryInterface
{
    /**
     * @var UserSurveyIncident
     */
    protected $incident;


    /**
     * ClientRepository constructor.
     * @param UserBrand $user
     */
    public function __construct(UserSurveyIncident $incident)
    {
        $this->incident = $incident;
    }

    /**
     * @param PutIncidentsDataType $putData
     * @return mixed
     */
    public function create(PutIncidentsDataType $putData)
    {
        $this->incident->create(
            [   'user_survey_id'       => $putData->userSurveyId,
                'hotel_staff_id'       => $putData->hotelStaffId,
                'incident_text'        => $putData->incidentText,
            ]
        );
    }
}
