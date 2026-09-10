<?php

namespace App\Http\Resources\Brands;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Brands\BrandInfoResource;
use Illuminate\Support\Facades\Log;

class BrandInfoResourcePaginate extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $brandInfo = $this->brandInfo;

        return [
            'id'                => $this->id,
            'name'              => $brandInfo->nombre,
            'logo'              => $brandInfo->logo,
            'background_color'  => $this->background_color,
            'children'          => BrandInfoResource::collection($this->children)
        ];
    }
}
