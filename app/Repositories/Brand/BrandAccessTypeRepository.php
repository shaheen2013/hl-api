<?php

namespace App\Repositories\Brand;

use App\BrandAccessType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class BrandAccessTypeRepository implements BrandAccessTypeRepositoryInterface
{
    protected $brandAccessType;

    public function __construct(BrandAccessType $brandAccessType)
    {
        $this->brandAccessType = $brandAccessType;
    }

    public function get($brandId)
    {
        $cacheName = 'brand_access_type_' . $brandId;
        $cacheTags = ['brand_access_' . $brandId];
        $cacheTime = Carbon::now()->addDays(30);

        $brandAccessType = Cache::tags($cacheTags)->get($cacheName);

        if (!$brandAccessType) {
            $brandAccessType =  $this->brandAccessType
                ->join("new_access_type", "new_access_type.id", "brand_access_type.access_type_id")
                ->where(["brand_id" => $brandId])
                ->orWhereNull('brand_id')
                ->orderBy('new_access_type.id')
                ->get();

            Cache::tags($cacheTags)->put($cacheName, $brandAccessType, $cacheTime);
        }

        return $brandAccessType;
    }

    public function put($data)
    {
        Cache::tags("brand_access_{$data->brandId}")->flush();

        return $this->brandAccessType->updateOrCreate(
            [
                "brand_id"          => $data->brandId,
                "access_type_id"    => $data->accessTypeId
            ],
            [
                "active"            => $data->active
            ]
        );
    }
}
