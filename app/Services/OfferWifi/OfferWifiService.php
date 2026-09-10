<?php

namespace App\Services\OfferWifi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Carbon\Carbon;
use App\Repositories\OfferWifi\OfferWifiRepositoryInterface;
use App\Http\Resources\OfferWifi\OfferWifiResource;
use App\Exceptions\OfferWifi\OfferWifiInvalidRequestException;
use App\Exceptions\OfferWifi\OfferWifiExistsException;
use App\Exceptions\OfferWifi\OfferWifiTooManyByDefaultException;
use App\Services\BrandService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class OfferWifiService
{
    protected $brandOfferWifi;
    protected $brandService;

    public function __construct(OfferWifiRepositoryInterface $brandOfferWifi, BrandService $brandService)
    {
        $this->brandOfferWifi = $brandOfferWifi;
        $this->brandService = $brandService;
    }

    /***
     * @param int $brandID
     * @param string $lang
     * @param string $validFrom
     * @param string $validTo
     * @param string $brand_type
     * @return JsonResource
     * @throws OfferWifiInvalidRequestException
     */

    public function getBrandOffersWifi(int $brandID, ?string $lang, ?string $validFrom, ?string $validTo, ?string $customerType): JsonResource
    {
        try {
            $timezone = $this->brandService->getBrandTimeZone($brandID);

            $validFrom = !empty($validFrom) ? Carbon::createFromFormat('Y-m-d', $validFrom, 'UTC')->setTimezone($timezone)->setTime(0, 0, 0) : null;
            $validTo = !empty($validTo) ? Carbon::createFromFormat('Y-m-d', $validTo, 'UTC')->setTimezone($timezone)->setTime(0, 0, 0) : null;
        } catch (InvalidArgumentException $exception) {
            throw new OfferWifiInvalidRequestException('Invalid date format', [
                'error'      => $exception->getMessage(),
                'valid_from' => $validFrom,
                'validTo'    => $validTo
            ]);
        }

        if ($validFrom || $validTo) {
            $offersByBrand = $this->getBrandOffersWifiByRange([
                'brand_id'     => $brandID,
                'valid_from'   => $validFrom,
                'valid_to'     => $validTo,
                'customerType' => $customerType
            ]);
        } else {
            $offersByBrand = $this->brandOfferWifi->getBrandOffersWifi($brandID);
        }

        return new OfferWifiResource($offersByBrand, $lang);
    }

    /***
     * @param int $brandID
     * @param array $wifiOffers
     * @return JsonResponse
     * @throws OfferWifiInvalidRequestException
     * @throws OfferWifiExistsException
     */

    public function setOffersWifi(int $brandID, array $wifiOffers): JsonResponse
    {
        $existOffers = [];
        $inconsistentDates = [];
        $offersToInsert = [];
        $accommodatedOffersDefault = 0;
        $nonAccommodatedOffersDefault = 0;

        foreach ($wifiOffers as $wifiOffer) {
            $wifiOffer['brand_id'] = $brandID;

            if ($wifiOffer['is_default']) {
                if ($wifiOffer['accommodated']) {
                    $accommodatedOffersDefault ++;
                }
                if ($wifiOffer['non_accommodated']) {
                    $nonAccommodatedOffersDefault ++;
                }

                $wifiOffer['valid_from'] = null;
                $wifiOffer['valid_to'] = null;
            }

            if ((empty($wifiOffer['valid_from']) ^ empty($wifiOffer['valid_to']))) {
                $inconsistentDates[] = $wifiOffer;
                break;
            }

            if (!empty($wifiOffer['valid_from']) && !empty($wifiOffer['valid_to'])) {
                $validFrom = Carbon::createFromFormat('Y-m-d', $wifiOffer['valid_from']);
                $validTo = Carbon::createFromFormat('Y-m-d', $wifiOffer['valid_to']);
                if ($validFrom->diffInHours($validTo, false) < 0) {
                    $tempDate = $wifiOffer['valid_from'];
                    $wifiOffer['valid_from'] = $wifiOffer['valid_to'];
                    $wifiOffer['valid_to'] = $tempDate;
                }

                if ($this->checkOverlapDates($wifiOffers, $wifiOffer)) {
                    $existOffers[] = $wifiOffer;
                    break;
                }
            }
            $offersToInsert[] = $wifiOffer;
        }

        if ($accommodatedOffersDefault > 1 || $nonAccommodatedOffersDefault > 1) {
            throw new OfferWifiTooManyByDefaultException(
                'More than one default',
                ["accommodated" => $accommodatedOffersDefault, "non_accommodated" => $nonAccommodatedOffersDefault],
                409
            );
        }

        if (count($existOffers)) {
            throw new OfferWifiExistsException('Offer exists', $existOffers, 409);
        }

        if (count($inconsistentDates)) {
            throw new OfferWifiInvalidRequestException('Information about dates is inconsistent', $inconsistentDates, 409);
        }

        $wifiOffersCreated = $this->setWifiOffers($offersToInsert);

        $response = new OfferWifiResource($wifiOffersCreated, null);

        if (empty($wifiOffersCreated)) {
            return $response
                ->response()
                ->setStatusCode(204);
        }

        return $response
            ->response()
            ->setStatusCode(201);
    }

    /***
     * @param array $wifiOffer
     * @param bool $excludeSelf
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function getBrandOffersWifiByRange(array $wifiOffer, bool $excludeSelf = false, bool $withDefault = true): Collection
    {
        return $this->brandOfferWifi->getBrandOffersWifiByRange($wifiOffer, $excludeSelf, $withDefault);
    }

    /***
     * @param int $brandWifiOfferID
     * @return bool
     */

    public function delete(int $brandWifiOfferID): bool
    {
        $deleted = $this->brandOfferWifi->delete($brandWifiOfferID);
        if ($deleted) {
            Log::info('Wifi offer deleted: ', [
                'id' => $brandWifiOfferID
            ]);
        }

        return $deleted;
    }

    /***
     * @param array $wifiOffer
     * @return void|Model
     */

    private function setWifiOffers(array $wifiOffers): ?array
    {
        $created = [];

        foreach ($wifiOffers as $wifiOffer) {
            if (isset($wifiOffer['id'])) {
                $id = $wifiOffer['id'];
                unset($wifiOffer['id']);
                $this->brandOfferWifi->update($id, $wifiOffer);
            } else {
                $created[] = $this->brandOfferWifi->create($wifiOffer);
            }
        }

        return $created;
    }

    private function checkOverlapDates(array $allOffers, $actualOffer): bool
    {
        foreach ($allOffers as $remainingOffer) {
            if (((Arr::get($remainingOffer, 'accommodated') && $actualOffer['accommodated']) ||
                    (Arr::get($remainingOffer, 'non_accommodated') && $actualOffer['non_accommodated'])) &&
                Arr::get($remainingOffer, 'id') != Arr::get($actualOffer, 'id') &&
                !empty(Arr::get($remainingOffer, 'valid_from')) &&
                !empty(Arr::get($remainingOffer, 'valid_to')) &&
                $remainingOffer['valid_from'] <= $actualOffer['valid_to'] &&
                $remainingOffer['valid_to'] >= $actualOffer['valid_from']
            ) {
                return true;
            }
        }

        return false;
    }
}
