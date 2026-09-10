<?php

namespace App\Repositories\Satisfactions;

use App\Types\Survey\PutIncidentsDataType;

interface IncidentRepositoryInterface
{
    /**
     * @param PutIncidentsDataType $putData
     * @return mixed
     */
    public function create(PutIncidentsDataType $putData);
}
