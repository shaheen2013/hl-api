<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "product_id" => $this->product_id,
            "active" => (bool)$this->active,
            "name" =>  str_replace("_", " ", $this->name)
        ];
    }
}
