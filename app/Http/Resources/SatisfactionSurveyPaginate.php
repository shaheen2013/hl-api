<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\SatisfactionSurvey;
use App\Repositories\Visits\UserBrandRepository;
use App\Services\Visits\VisitService;
use Illuminate\Support\Facades\Log;

class SatisfactionSurveyPaginate extends ResourceCollection
{
    private $pagination;
    private $visitService;
    private $userBrandRepository;
    
    public function __construct($resource, $averageScore, VisitService $visitService, UserBrandRepository $userBrandRepository)
    {
        $this->visitService = $visitService;
        $this->userBrandRepository = $userBrandRepository;
        
        if (method_exists($resource, 'currentPage')) {
            $this->pagination = [
                'total' => $resource->total(),
                'count' => $resource->count(),
                'perPage' => $resource->perPage(),
                'currentPage' => $resource->currentPage(),
                'totalPages' => $resource->lastPage(),
                'averageScore' => round($averageScore, 2)
            ];


            $resource = $resource->getCollection();
        }

        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return new SatisfactionSurvey($item, $this->visitService, $this->userBrandRepository);
            }),
            'pagination' => $this->pagination,
        ];
    }
}
