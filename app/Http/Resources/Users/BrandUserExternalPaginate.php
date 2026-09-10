<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\ResourcePaginate;
use App\Http\Resources\Users\BrandUserExternalResource;

class BrandUserExternalPaginate extends ResourcePaginate
{
    public function __construct($resource)
    {
        // Set externalApi variable
        $this->externalApi = true;
        // Construct parente
        parent::__construct($resource);
        // Inject the resourceFilter
        $this->resourceFilter = BrandUserExternalResource::collection($this->collection);
    }
}
