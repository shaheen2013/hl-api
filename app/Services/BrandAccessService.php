<?php

namespace App\Services;

use App\Repositories\Brand\BrandAccessRepository;
use App\Repositories\Brand\BrandAccessTypeRepository;
use App\Repositories\Brand\BrandRepository;
use Illuminate\Support\Facades\Log;

class BrandAccessService
{
    protected $brandAccessRepository;
    protected $brandAccessTypeRepository;
    protected $brandRepository;

    public function __construct(BrandAccessRepository $brandAccessRepository, BrandAccessTypeRepository $brandAccessTypeRepository, BrandRepository $brandRepository)
    {
        $this->brandAccessRepository = $brandAccessRepository;
        $this->brandAccessTypeRepository = $brandAccessTypeRepository;
        $this->brandRepository = $brandRepository;
    }

    public function get($brandID, $accessType)
    {
        $brand = $this->brandRepository->get($brandID);

        if ($brand) {
            return $this->brandAccessRepository->get($brand, $accessType);
        } else {
            Log::warning("Brand not found on brand_access", ["brandID" => $brandID, "brand" => $brand]);
        }
    }

    public function store($brandID, $codes, $accessType)
    {
        $brand = $this->brandRepository->get($brandID);

        if ($brand) {
            return $this->brandAccessRepository->store($brand, $codes, $accessType);
        }
    }

    public function getAccessTypes($brandId)
    {
        return $this->brandAccessTypeRepository->get($brandId);
    }

    public function putAccessTypes($data)
    {
        return $this->brandAccessTypeRepository->put($data);
    }
}
