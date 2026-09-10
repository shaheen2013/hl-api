<?php

namespace App\Repositories\OfferWifi;

use App\BrandOfferWifi;
use Illuminate\Database\Eloquent\Collection;

interface OfferWifiRepositoryInterface
{
    /**
     * @param int $brandID
     * @return Collection
     */
    public function getBrandOffersWifi(int $brandID): Collection;

    /**
     * @param array $wifiOffer
     * @return null|BrandOfferWifi
     */
    public function create(array $wifiOffer): ?BrandOfferWifi;

    /**
     * @param int $id
     * @param array $wifiOffer
     * @return void
     */
    public function update(int $id, array $wifiOffer): void;

    /**
     * @param array $wifiOffer
     * @param bool $excludeSelf,
     * @param bool $withDefault
     * @return Collection
     */
    public function getBrandOffersWifiByRange(array $wifiOffer, bool $excludeSelf = false, bool $withDefault = true): Collection;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param int $brandID
     * @return void
     */
    public function resetIsDefaultByBrand(int $brandID): void;
}
