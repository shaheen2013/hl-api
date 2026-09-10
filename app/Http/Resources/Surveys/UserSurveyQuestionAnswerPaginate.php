<?php

namespace App\Http\Resources;

use App\Http\Resources\ResourcePaginate;
use App\Http\Resources\UserSurveyQuestionAnswer;

class UserSurveyQuestionAnswerPaginate extends ResourcePaginate
{
    public function __construct($resource)
    {
        // Construct parente
        parent::__construct($resource);
        // Inject the resourceFilter
        $this->resourceFilter = UserSurveyQuestionAnswer::collection($this->collection);
    }
}
