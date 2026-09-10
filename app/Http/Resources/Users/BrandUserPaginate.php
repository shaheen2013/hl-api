<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\ResourcePaginate;
use App\Http\Resources\Users\BrandUserResource;

class BrandUserPaginate extends ResourcePaginate
{
    public function __construct($resource)
    {
        // Construct parente
        parent::__construct($resource);
        // Inject the resourceFilter
        $this->resourceFilter = BrandUserResource::collection($this->collection);
    }
}
