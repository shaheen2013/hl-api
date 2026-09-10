<?php

namespace Tests\Unit;

use App\Brand;
use App\Hotel;
use App\Language;
use Tests\TestCase;

class BrandUrlLanguageControllerTest extends TestCase
{
    public function testCanCreateBrandUrlLanguage()
    {

        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $language = Language::where('id', '=', 1)->first();
        $url = "http://urldetest2.com";

        $this->call('POST', "/api/brands/$brand->id/languages/$language->name/url", ['url' => $url])
            ->assertStatus(200);
    }

    public function testCanListBrandUrlLanguages()
    {
        $this->json('GET', '/api/brands/url/all')->assertStatus(200);
    }

    public function testCanShowBrandUrlLanguageByBrandId()
    {

        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();

        $this->json('GET', "/api/brands/$brand->id/url")
            ->assertStatus(200);
    }

    public function testCanShowBrandUrlLanguageByBrandIdAndByLanguageName()
    {

        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $language = Language::where('id', '=', 1)->first();

        $this->json('GET', "/api/brands/$brand->id/languages/$language->name/url")
            ->assertStatus(200);
    }

    public function testCanUpdateBrandUrlLanguage()
    {

        $hotel = factory(Hotel::class)->create();

        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $language = Language::where('id', '=', 1)->first();
        $url = "http://testUpdateando.es";

        $this->call('PUT', "/api/brands/$brand->id/languages/$language->name/url", ['url' => $url])
            ->assertStatus(200);
    }

    public function testCanUpdateBrandUrlLanguageList()
    {

        $hotel = factory(Hotel::class)->create();
        $brand = Brand::where('hotel_id', '=', $hotel->id)->first();
        $language = Language::where('id', '=', 1)->first();
        $url = "http://testUpdateando.es";

        $this->call('PUT', "/api/brands/languages/url", array(['brand_id' => $brand->id, 'name' => $language->name, 'url' => $url]))
            ->assertStatus(200);
    }
}
