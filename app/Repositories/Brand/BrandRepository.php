<?php

namespace App\Repositories\Brand;

use App\AutomaticReports;
use App\Brand;
use App\BrandUrlLanguage;
use App\HotelSatisfaction;
use App\HotelWifiIntegrations;
use App\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class BrandRepository implements BrandRepositoryInterface
{
    protected $brand;
    protected $brandUrlLanguage;

    public function __construct(Brand $brand, BrandUrlLanguage $brandUrlLanguage)
    {
        $this->brand = $brand;
        $this->brandUrlLanguage = $brandUrlLanguage;
    }

    public function get($brandID)
    {
        $tagName = ["brand_" . $brandID];
        $cacheName = "brand_" . $brandID;

        $brand = Cache::tags($tagName)->get($cacheName);
        if (!$brand) {
            $brand = $this->brand->findOrFail($brandID);
            Cache::tags($tagName)->put($cacheName, $brand, now()->addDays(10));
        }
        return $brand;
    }

    public function getByUuid($uuid)
    {
        return $this->brand->where('uuid', '=', $uuid)->firstOrFail();
    }

    public function insertBrandUrlLanguage(int $brand_id, string $name, string $url)
    {

        $language_id = Language::where('name', '=', $name)
            ->select('id')
            ->first()
            ->id;

        $this->brandUrlLanguage->brand_id = $brand_id;
        $this->brandUrlLanguage->language_id = $language_id;
        $this->brandUrlLanguage->url = $url;

        $this->brandUrlLanguage->save();
    }

    public function getAllBrandUrlLanguages()
    {
        return $this->brandUrlLanguage->all();
    }

    public function getUrlsByBrandId(int $brand_id)
    {
        $queryResult = DB::table('brand_url_language')
            ->join('languages', 'brand_url_language.language_id', '=', 'languages.id')
            ->where('brand_id', $brand_id)
            ->select('brand_url_language.id', 'brand_id', 'language_id', 'url', 'languages.name')
            ->get();

        return $queryResult;
    }

    public function getUrlsByBrandIdAndLanguageId(int $brand_id, int $language_id)
    {
        return $this->brandUrlLanguage
            ->where('brand_id', $brand_id)
            ->where('language_id', $language_id)
            ->get();
    }

    public function getUrlsByBrandIdAndLanguage(int $brand_id, string $name)
    {
        $cacheName = 'urls_' . $brand_id . '_language_' . $name;
        $cacheTags = ['urls_' . $brand_id];
        $cacheTime = Carbon::now()->addDays(7);

        $urls = Cache::tags($cacheTags)->get($cacheName);

        if (!$urls) {
            $urls = $this->brandUrlLanguage
                ->join('languages', 'brand_url_language.language_id', '=', 'languages.id')
                ->where('brand_id', $brand_id)
                ->where('name', $name)
                ->select('brand_url_language.id', 'brand_id', 'language_id', 'url', 'name')
                ->get();

            Cache::tags($cacheTags)->put($cacheName, $urls, $cacheTime);
        }

        return $urls;
    }

    public function updateBrandInfo(int $brand_id, $brand_request)
    {
        $brand = $this->brand->findOrFail($brand_id);
        if (isset($brand_request['background_color'])) {
            $brand->update($brand_request);
        }

        if ($brand->hotel) {
            $brand->hotel->update($brand_request);
        }

        Cache::tags(["brand_" . $brand_id, "brandInfo_" . $brand_id])->flush();
        return $brand;
    }

    public function updateBrandUrlLanguage(int $brand_id, string $name, string $url)
    {
        $language_id = Language::where('name', '=', $name)
            ->select('id')
            ->first()
            ->id;

        $this->brandUrlLanguage
            ->where('brand_id', $brand_id)
            ->where('language_id', $language_id)
            ->update(
                ['url' => $url]
            );
    }

    public function updateUrlsLanguageList(array $brandUrlLangList)
    {
        foreach ($brandUrlLangList as $brandUrlLangObj) {
            $brand_id = $brandUrlLangObj->brand_id;

            $language_id = Language::where('name', '=', $brandUrlLangObj->name)
                ->select('id')
                ->first()
                ->id;

            $url = $brandUrlLangObj->url;

            BrandUrlLanguage::updateOrCreate(
                ['brand_id' => $brand_id, 'language_id' => $language_id],
                ['url' => $url]
            );

            Cache::tags("urls_" . $brand_id)->flush();
        }
    }


    public function deleteBrandUrlLanguageByBrandIdAndLanguageName(int $brand_id, string $name)
    {

        $language_id = Language::where('name', '=', $name)
            ->select('id')
            ->first()
            ->id;

        $this->brandUrlLanguage
            ->where('brand_id', $brand_id)
            ->where('language_id', $language_id)
            ->firstOrFail()
            ->delete();
    }

    public function unarchiveBrand(int $status, Brand $brand)
    {
        $brand->hotel->where('id', $brand->hotel_id)->update(['activated' => $status]);
        Cache::tags(["brand_" . $brand->id, "brandInfo_" . $brand->id])->flush();
    }

    public function archiveBrand(int $status, Brand $brand)
    {
        try {
            $hotelId = $brand->hotel_id;
            $brand->hotel->where('id', $hotelId)->update([
                'birthdayAlertEmails' => null,
                'loyalty_emails' => '',
                'activated' => $status
            ]);
            // Delete emails configuration
            AutomaticReports::where('hotel_id', $hotelId)->delete();
            HotelSatisfaction::where('id_hotel', $hotelId)->update(['warning_email' => '']);
            HotelWifiIntegrations::where('hotel_id', $hotelId)->delete();
            Cache::tags(["brand_" . $brand->id, "brandInfo_" . $brand->id])->flush();
        } catch (\Exception $e) {
            Log::error("Error archiving: " . $e->getMessage());
        }

        return json_encode([
            'message' => 'Brand archived successfully',
            'status' => 'success',
        ]);
    }
}
