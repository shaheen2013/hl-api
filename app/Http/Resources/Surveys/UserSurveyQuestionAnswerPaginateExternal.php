<?php

namespace App\Http\Resources;

use App\Http\Resources\ResourcePaginate;
use App\Http\Resources\UserSurveyQuestionAnswerExternal;
use App\Services\Visits\VisitService;
use App\Repositories\Visits\UserBrandRepository;

class UserSurveyQuestionAnswerPaginateExternal extends ResourcePaginate
{
    protected $visitService;
    protected $userBrandRepository;

    public function __construct($resource, VisitService $visitService, UserBrandRepository $userBrandRepository)
    {
        // Set externalApi variable
        $this->externalApi = true;
        // Construct parent
        $this->visitService = $visitService;
        $this->userBrandRepository = $userBrandRepository;

        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return new UserSurveyQuestionAnswerExternal($item, $this->visitService, $this->userBrandRepository);
            }),
        ];
    }
}
