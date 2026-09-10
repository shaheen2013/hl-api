<?php

namespace App\Http\Resources\Staff;

use App\Http\Resources\ResourcePaginate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StaffResourcePaginate extends ResourcePaginate
{
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->resourceFilter = StaffResource::collection($this->collection);
    }
}
