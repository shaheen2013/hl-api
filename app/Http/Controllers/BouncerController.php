<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BrandService;
use Illuminate\Support\Facades\App;
use App\Services\Bouncer\BouncerService;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BouncerController extends Controller
{
    private $bouncerService;
    private $brandService;

    private $locales = ['en', 'es', 'de', 'it', 'ca', 'fr', 'zh'];
    private $fallBackBackground =  "#715aff";

    public function __construct(BouncerService $bouncerService, BrandService $brandService)
    {
        $this->bouncerService =  $bouncerService;
        $this->brandService =  $brandService;
    }

    public function redirectView(Request $request, $brandId)
    {
        // Set locale to view, if locale not exist in directory "resources/lang" put the default lang
        // configure in "config/app->fallback_locale"

        $locale = $request->getPreferredLanguage($this->locales);
        $isInHotel = $this->bouncerService->isInHotel($request);
        $formParams = $request->input();
        $method = $request->method();
        $userAgent = $request->header('User-Agent');
        $isApple = $this->bouncerService->checkAppleDevice($userAgent, $request->header('cloudfront-is-ios-viewer') === "true");
        $isAndroid = $request->header('cloudfront-is-android-viewer') === "true";
        $androidVersion = $this->bouncerService->getAndroidVersion($userAgent, $isAndroid);
        $isOldAndroid = $this->bouncerService->checkOldAndroid($androidVersion);
        $isInCna = $this->bouncerService->checkCna($userAgent, $isAndroid, $isApple);
        App::setLocale($locale);

        $cacheName = 'bouncer_' . $brandId . '_formParams_' .  serialize($formParams) . '_locale_' . $locale . '_isInHotel_' . $isInHotel . '_method_' . $method . '_isCna' . $isInCna;
        $cacheTags = ['bouncer_' . $brandId];
        $cacheTime = Carbon::now()->addDays(1);

        $bouncerViewCache = Cache::tags($cacheTags)->get($cacheName);

        if (!$bouncerViewCache) {
            // get variables from mikrotik
            $loginLink = $request->input('link-login-only');
            $macEscaped = $request->input('mac-esc');
            $uuid = Str::uuid()->toString();


            $brandInfo = $this->getBrandInfo($brandId);
            $baseUrl = config('services.bouncer.url') . '/' . $brandId . '/checkin';
            $checkinUrl = $fallbackUrl =  $baseUrl . "?in-hotel=" . $isInHotel . '&uuid=' . $uuid;

            if ($isInHotel) {
                $loginLinkUrl = $loginLink . '?username=T-' . $macEscaped . '&dst=' . urlencode($brandInfo["url"]["checkin"] . '?uuid=' . $uuid);
                $checkinUrl = config('services.bouncer.url') . '/' . $brandId . '/checkin?url=' .  urlencode($loginLinkUrl);

                // On modern Android devices we redirect out of the CNA with an intent.
                // When the URL is opened in chrome and the checkin page is visited it will be given internet via Trial and redirected to the autocheckin.
                if ($isAndroid) {
                    $checkinUrl = 'intent://' . preg_replace("(^https?://)", "", config('services.bouncer.url')) . '/' . $brandId . '/checkin?url=' .  urlencode($loginLinkUrl) . '&in-hotel=' . $isInHotel . '&uuid=' . $uuid . '#Intent;scheme=https;end';

                    // In case the intent does not work on the device in question, a fallback url to the redirect page of the checkin is defined.
                    // On this page there will be steps to exit the CNA by hand, opening a link in a normal browser. This link goes to the same page,
                    // passing by parameter the url of the trial so that when it opens in chrome it gives internet and redirects to the autocheckin.
                    $fallbackUrl = $baseUrl . '?url=' . urlencode($baseUrl . "?url=" . urlencode($loginLinkUrl) . '&in-hotel=' . $isInHotel . '&uuid=' . $uuid) . '&in-hotel=' . $isInHotel . '&uuid=' . $uuid;
                }

                // In Apple devices we will give the trial in the first step in order to redirect the user out of the CNA when clicking on the following link
                if ($isApple) {
                    $checkinUrl = $loginLink . '?username=T-' . $macEscaped . '&dst=' . urlencode($baseUrl . '?in-hotel=' . $isInHotel . '&uuid=' . $uuid);
                }
            }

            $bouncerViewCache = view(
                'bouncer',
                [
                    "method" => $method,
                    "formParams" => $formParams,
                    "appUrl" => $brandInfo["url"]["app"],
                    "isInHotel" => $isInHotel,
                    "hotel" => $brandInfo["hotel"],
                    "locale" => $locale,
                    "brandId" => $brandId,
                    "mainColor" => $brandInfo["backgroundColor"],
                    "textColor" => $this->bouncerService->calcTextColor($brandInfo["backgroundColor"]),
                    "mainColorDarken" => $this->bouncerService->adjustBrightness($brandInfo["backgroundColor"], 20),
                    "bgImages" => $brandInfo["bgImages"],
                    "loginLink" => $loginLink,
                    "checkinUrl" => $checkinUrl,
                    "isAndroid" => $isAndroid,
                    "isInCna" => $isInCna,
                    "fallbackUrl" => $fallbackUrl
                ]
            )->render();

            Log::info("Caching new bouncer page", [
                "brand_id"          => $brandId,
                "isInHotel"         => $isInHotel,
                "isAndroid"         => $isAndroid,
                "androidVersion"    => $androidVersion,
                "isOldAndroid"      => $isOldAndroid,
                "isApple"           => $isApple,
                "isInCna"           => $isInCna,
                "method"            => $method,
                "formParams"        => $formParams,
                "loginLink"         => $loginLink,
                "checkinUrl"        => $checkinUrl,
                "userAgent"         => $userAgent,
                'bouncerUuid'       => $uuid,
                "fallbackUrl" => $fallbackUrl
            ]);

            Cache::tags($cacheTags)->put($cacheName, $bouncerViewCache, $cacheTime);
        } else {
            Log::info("Get bouncer page from cache", [
                "brand_id"      => $brandId,
                "isInHotel"     => $isInHotel,
                "method"        => $method,
                "formParams"    => $formParams,
                "userAgent"     => $userAgent
            ]);
        }

        return $bouncerViewCache;
    }

    public function checkinView(Request $request, $brandId)
    {
        // Set locale to view, if locale not exist in directory "resources/lang" put the default lang
        // configure in "config/app->fallback_locale"

        $locale = $request->getPreferredLanguage($this->locales);
        App::setLocale($locale);

        $brandInfo = $this->getBrandInfo($brandId);

        $userAgent = $request->header('User-Agent');
        $isApple = $this->bouncerService->checkAppleDevice($userAgent, $request->header('cloudfront-is-ios-viewer') === "true");
        $isAndroid = $request->header('cloudfront-is-android-viewer') === "true";
        $androidVersion = $this->bouncerService->getAndroidVersion($userAgent, $isAndroid);
        $isOldAndroid = $this->bouncerService->checkOldAndroid($androidVersion);
        $isInCna = $this->bouncerService->checkCna($userAgent, $isAndroid, $isApple);

        $params = $request->input();

        // Sometimes when the trial does the redirection from the mikrotik transforms & per amp; and it is taken as part of the parameter
        $uuid = data_get($params, "uuid", data_get($params, "amp;uuid"));
        $isInHotel = data_get($params, "in-hotel") ?? false;
        $url = data_get($params, "url") ?? $brandInfo["url"]["checkin"] . '?uuid=' . $uuid;

        Log::info("Checkin page displayed", [
            "brand_id"          => $brandId,
            "isInHotel"         => $isInHotel,
            "isAndroid"         => $isAndroid,
            "androidVersion"    => $androidVersion,
            "isOldAndroid"      => $isOldAndroid,
            "isApple"           => $isApple,
            "isInCna"           => $isInCna,
            "bouncerUuid"       => $uuid,
            "redirectUrl"       => $url,
            "userAgent"         => $request->header('User-Agent')
        ]);

        return view(
            'checkinRedirect',
            [
                "url" => $url,
                "hotel" => $brandInfo["hotel"],
                "mainColor" => $brandInfo["backgroundColor"],
                "textColor" => $this->bouncerService->calcTextColor($brandInfo["backgroundColor"]),
                "mainColorDarken" => $this->bouncerService->adjustBrightness($brandInfo["backgroundColor"], 20),
                "bgImages" => $brandInfo["bgImages"],
                "isApple" => $isApple,
                "isOldAndroid" => $isOldAndroid,
                "isInHotel" => $isInHotel,
                "isInCna"           => $isInCna,
            ]
        );
    }
    private function getBrandInfo(int $brandId)
    {
        $brand = $this->brandService->getBrand($brandId);
        $url = $this->bouncerService->getUrls($brand);
        $hotel = $brand->hotel;
        $bgImages = $this->bouncerService->getBackgroundImages($hotel);

        $backgroundColor = $brand->background_color;

        if (!$backgroundColor) {
            $backgroundColor =  $this->fallBackBackground;
        }

        return [
            "hotel" => $hotel,
            "url" => $url,
            "bgImages" => $bgImages,
            "backgroundColor" => $backgroundColor
        ];
    }
}
