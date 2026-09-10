<?php

namespace App\Http\Resources\Loyalty;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @property mixed hotel
 * @property mixed id
 */
class OffersResourceCollection extends ResourceCollection
{
    protected $lang;

    public function lang($value)
    {
        $this->lang = $value;
        return $this;
    }

    public function toArray($request = null)
    {
        return $this->collection->map(function (OffersResource $resource) use ($request) {
            return $resource->lang($this->lang);
        })->all();
    }
}
