<?php

namespace App\Http\Resources\Brands;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BrandAccessTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "active" => $this->active,
        ];
    }
}
