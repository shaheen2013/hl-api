<?php

namespace App\Services;

use App\Hotel;
use App\Brand;
use App\BrandProduct;
use App\Events\Brand\BrandArchived;
use App\Events\Brand\BrandUpdated;
use App\HotelSatisfaction;
use App\Http\Resources\Brands\BrandInfoResource;
use App\Http\Resources\Brands\BrandInfoResourcePaginate;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Repositories\Products\BrandProductRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class BrandService
{
    protected $brandRepository;
    protected $brandProductRepository;

    public function __construct(BrandRepositoryInterface $brandRepository, BrandProductRepository $brandProductRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->brandProductRepository = $brandProductRepository;
    }

    public function getBrand($id)
    {
        return $this->brandRepository->get($id);
    }

    public function getBrandByUuid($uuid)
    {
        $brand = cache("brand_" . $uuid);
        if (!$brand) {
            $brand = $this->brandRepository->getByUuid($uuid);
            cache(["brand_" . $uuid => $brand], now()->addDays(10));
        }
        return $brand;
    }

    public function getBrandTimeZone($id)
    {
        $brand = $this->getBrand($id);

        return $brand->hotel ?
            $brand->hotel->timeZone->time_zone :
            $brand->children[0]->hotel->timeZone->time_zone;
    }

    public function getStayTime($id)
    {
        $brand = cache("brand_" . $id);
        if (!$brand) {
            $brand = $this->brandRepository->get($id);
            cache(["brand_" . $id => $brand], now()->addDays(10));
        }

        return data_get($brand, 'brandInfo.stayTime', 7);
    }

    public function getBrandSatisfactionConfiguration($brand)
    {
        return HotelSatisfaction::where('id_hotel', $brand->hotel_id)->first();
    }

    public function getBrandPermissions($brand)
    {
        return BrandProduct::where('id_hotel', $brand->hotel_id)->first();
    }

    public function setBrandUrlLanguage(int $brand_id, string $langName, string $url)
    {
        $this->brandRepository->insertBrandUrlLanguage($brand_id, $langName, $url);
    }

    public function getAllBrandUrlLanguages()
    {
        return $this->brandRepository->getAllBrandUrlLanguages();
    }

    public function getUrlsByBrandId(int $brand_id)
    {
        return $this->brandRepository->getUrlsByBrandId($brand_id);
    }

    public function getUrlsByBrandIdAndLanguageId(int $brand_id, int $language_id)
    {
        return $this->brandRepository->getUrlsByBrandIdAndLanguageId($brand_id, $language_id);
    }

    public function getUrlsByBrandIdAndLanguage(int $brand_id, string $name)
    {
        return $this->brandRepository->getUrlsByBrandIdAndLanguage($brand_id, $name);
    }

    public function updateBrandUrlLanguage(int $brand_id, string $name, string $url)
    {
        $this->brandRepository->updateBrandUrlLanguage($brand_id, $name, $url);
    }

    public function updateUrlsLanguageList(array $brandUrlLangList)
    {
        $this->brandRepository->updateUrlsLanguageList($brandUrlLangList);
    }

    public function deleteBrandUrlLanguageByBrandIdAndLanguageName(int $id, string $name)
    {
        $this->brandRepository->deleteBrandUrlLanguageByBrandIdAndLanguageName($id, $name);
    }

    public function getBrandInfo(int $brand_id)
    {
        $brand = $this->getBrand($brand_id);

        if (!$brand->children->isEmpty()) {
            return new BrandInfoResourcePaginate($brand);
        } else {
            return new BrandInfoResource($brand);
        }
    }

    public function updateBrandInfo(int $id, $brand_request)
    {
        try {
            $brand_update = $this->brandRepository->updateBrandInfo($id, $brand_request);
            $this->sendBrandUpdatedEvent($id);
        } catch (Exception $e) {
            Log::error("Error", ["message" => "Failed to update BrandInfo", "request" => $brand_request, "error" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine()]);
        }

        return [
            "brand" => $brand_update,
        ];
    }

    public function sendBrandUpdatedEvent(int $brandId)
    {
        $brandInfo = [
            "brand" => $this->getBrandInfo($brandId),
            "account" => $this->getAccountInfo($brandId)
        ];

        event(new BrandUpdated($brandInfo));
    }

    public function getAccountInfo(int $brandId)
    {
        $brand = $this->getBrand($brandId);
        return $brand->parent;
    }

    public function archiveBrand(int $status, int $brandId)
    {
        try {
            $brand = $this->getBrand($brandId);
            $account = $brand->parent;
            if ($status == 1) {
                $this->brandRepository->unarchiveBrand($status, $brand);
                $this->sendBrandUpdatedEvent($brandId); // Send event if brand is unarchived
            } elseif ($status == 0) {
                $totalActiveHotels = $this->countActivatedHotelsInChain($account);
                $this->brandProductRepository->updateProductStatus($brandId);
                $this->brandRepository->archiveBrand($status, $brand);
                $this->sendBrandUpdatedEvent($brandId); // Send event if brand is archived
                return json_encode([
                    'active_hotel_count' => $totalActiveHotels,
                ]);
            }
        } catch (Exception $e) {
            Log::error("Error", ["message" => $e->getMessage()]);
        }
    }

    private function countActivatedHotelsInChain($account): int
    {
        $totalActiveHotels = $account->children->filter(function ($child) {
            return $child->brandInfo->activated == 1;
        })->count();

        return $totalActiveHotels;
    }
}
