<?php

namespace App\Repositories\Brand;

use App\BrandAccessType;
use App\Types\Brands\PutBrandAccessDataType;

interface BrandAccessTypeRepositoryInterface
{
    public function __construct(BrandAccessType $brandAccess);

    public function get(int $brandId);
    public function put(PutBrandAccessDataType $data);
}
