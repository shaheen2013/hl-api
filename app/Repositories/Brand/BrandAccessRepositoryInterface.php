<?php

namespace App\Repositories\Brand;

use App\BrandAccess;

interface BrandAccessRepositoryInterface
{
    public function __construct(BrandAccess $brandAccess);

    public function get($brandID, $accessType);
    public function store($brandID, $codes, $accessType);
}
