<?php

namespace App\Repositories\Brand;

use App\Brand;
use App\BrandUrlLanguage;
use App\Hotel;

interface BrandRepositoryInterface
{
    public function __construct(Brand $brand, BrandUrlLanguage $brandUrlLanguage);

    public function get($brandID);

    public function insertBrandUrlLanguage(int $brand_id, string $name, string $url);

    public function getAllBrandUrlLanguages();

    public function getUrlsByBrandId(int $brand_id);

    public function getUrlsByBrandIdAndLanguageId(int $brand_id, int $language_id);

    public function getUrlsByBrandIdAndLanguage(int $brand_id, string $name);

    public function updateBrandInfo(int $brand_id, $brand_request);

    public function updateBrandUrlLanguage(int $brand_id, string $name, string $url);

    public function updateUrlsLanguageList(array $brandUrlLangList);

    public function deleteBrandUrlLanguageByBrandIdAndLanguageName(int $brand_id, string $langName);

    public function archiveBrand(int $status, Brand $brand);

    public function unarchiveBrand(int $status, Brand $brand);
}
