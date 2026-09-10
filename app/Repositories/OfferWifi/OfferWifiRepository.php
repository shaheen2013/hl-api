<?php

namespace App\Repositories\OfferWifi;

use App\BrandOfferWifi;
use Illuminate\Database\Eloquent\Collection;

class OfferWifiRepository implements OfferWifiRepositoryInterface
{
    private $brandOfferWifi;

    public function __construct(BrandOfferWifi $brandOfferWifi)
    {
        $this->brandOfferWifi = $brandOfferWifi;
    }

    public function getBrandOffersWifi(int $brandID): Collection
    {
        return $this->brandOfferWifi
            ->where('brand_id', $brandID)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function getBrandOffersWifiByRange(array $wifiOffer, bool $excludeSelf = false, bool $withDefault = true): Collection
    {
        $query = $this->brandOfferWifi
            ->where('brand_id', $wifiOffer['brand_id']);

        if ($wifiOffer['valid_from'] && $wifiOffer['valid_to']) {
            $query->where(function ($query) use ($wifiOffer, $withDefault) {
                $query->where([
                    ['valid_from', '>=', $wifiOffer['valid_from']],
                    ['valid_from', '<=', $wifiOffer['valid_to']]
                ])->orWhere([
                    ['valid_from', '<=', $wifiOffer['valid_from']],
                    ['valid_to', '>=', $wifiOffer['valid_from']]
                ]);

                if ($withDefault) {
                    $query->orWhere('is_default', true);
                }
            });
        } elseif ($wifiOffer['valid_from']) {
            $query->where(function ($query) use ($wifiOffer, $withDefault) {
                $query->where('valid_from', '>=', $wifiOffer['valid_from']);
                if ($withDefault) {
                    $query->orWhere('is_default', true);
                }
            })->orderBy('valid_from', 'asc');
        } elseif ($wifiOffer['valid_to']) {
            $query->where(function ($query) use ($wifiOffer, $withDefault) {
                $query->where('valid_to', '<=', $wifiOffer['valid_to']);
                if ($withDefault) {
                    $query->orWhere('is_default', true);
                }
            })->orderBy('valid_to', 'asc');
        }

        if (isset($wifiOffer['customerType'])) {
            $query->where($wifiOffer['customerType'], 1);
        }

        if (isset($wifiOffer['id']) && $excludeSelf) {
            $query->where('id', '<>', $wifiOffer['id']);
        }

        return $query
            ->get();
    }

    public function update(int $id, array $wifiOffer): void
    {
        $this->brandOfferWifi
            ->where('id', $id)
            ->update($wifiOffer);
    }

    public function create(array $wifiOffer): ?BrandOfferWifi
    {
        if ($newWifiOffer = $this->brandOfferWifi->create($wifiOffer)) {
            return $newWifiOffer;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $offerWifi = $this->brandOfferWifi->findOrFail($id);

        return $offerWifi->delete();
    }

    public function resetIsDefaultByBrand(int $brandID): void
    {
        $this->brandOfferWifi
            ->where('brand_id', $brandID)
            ->update(
                [
                    'is_default' => false
                ]
            );
    }
}
