<?php

namespace App\Repositories\Brand;

use App\BrandAccess;

class BrandAccessRepository implements BrandAccessRepositoryInterface
{
    protected $brandAccess;
    const PREMIUMCODES = 'premium';

    public function __construct(BrandAccess $brandAccess)
    {
        $this->brandAccess = $brandAccess;
    }

    public function get($brand, $accessType)
    {
        $codes = $this->brandAccess->where(["brand_id" => $brand->id]);

        $codes = $accessType ?
            $codes->select("codes->{$accessType}->codes as codes")->first() :
            $codes->select("codes")->first();

        $codes = $codes ?
            array_map('json_decode', $codes->toArray()) :
            null;

        $parentCodes = $this->brandAccess->where(["brand_id" => $brand->parent_id])->first();
        if ($parentCodes && (!$accessType || $accessType == self::PREMIUMCODES)) {
            $parentCodes = array_map('json_decode', $parentCodes->toArray());

            $accessType == self::PREMIUMCODES ?
                data_set($codes, 'codes', data_get($parentCodes, 'codes.' . self::PREMIUMCODES . '.codes')) :
                data_set($codes, 'codes.' . self::PREMIUMCODES, data_get($parentCodes, 'codes.' . self::PREMIUMCODES));
        }

        return $codes;
    }

    public function store($brand, $codes, $accessType)
    {
        $brandID = data_get($codes, 'byParent') && $accessType == self::PREMIUMCODES ?
            $brand->parent_id :
            $brand->id;

        if (!data_get($codes, 'byParent') && $accessType == self::PREMIUMCODES) {
            $this->brandAccess->where(["brand_id" => $brand->parent_id])->delete();
        }

        $brandAccess = $this->brandAccess->firstOrCreate(["brand_id" => $brandID]);

        $brandAccess->forceFill(['codes->' . $accessType => $codes]);
        $brandAccess->save();
    }
}
