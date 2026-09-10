<?php

namespace App\Services\Bouncer;

use Illuminate\Http\Request;

class BouncerService
{
    private $redirectUrl;
    public function __construct()
    {
        $this->checkinUrl = config('services.autochekin');
        $this->hotelinkingAppUrl = config('services.hotelinking.url');
    }

    public function getUrls($brand): array
    {
        return [
            "checkin" => "{$this->checkinUrl}{$brand->id}",
            "app" => "{$this->hotelinkingAppUrl}stay-share/{$brand->hotel->hotelGuid->guid}/",
        ]
        ;
    }

    public function isInHotel(Request $request): bool
    {
        $requestValuesToMatch = ["mac", "username", "password", "auth_user", "room_number", "ip", "url"];

        $requestParams = array_map('strtolower', array_keys($request->input()));

        return !empty(array_intersect($requestValuesToMatch, $requestParams));
    }

    public function getAndroidVersion($userAgent, $isAndroid)
    {
        preg_match("/Android (\d+(?:\.\d+)*)/", $userAgent, $matches);

        return $isAndroid ? $matches[1] : null;
    }

    public function checkOldAndroid($androidVersion)
    {
        return $androidVersion ? version_compare($androidVersion, '11', '<') : null;
    }

    public function checkAppleDevice($userAgent, $isIos)
    {
        return $isIos || str_contains($userAgent, 'Mac OS X');
    }

    public function checkCna($userAgent, $isAndroid, $isApple)
    {
        if ($isApple) {
            $userAgent = strtolower($userAgent);

            return (strpos($userAgent, 'mozilla/') !== false) &&
                (strpos($userAgent, 'applewebkit/') !== false) &&
                (strpos($userAgent, 'safari') === false);
        } elseif ($isAndroid) {
            return str_contains($userAgent, 'Version/');
        } else {
            // In windows or Linux there is no CNA navigator
            return false;
        }
    }

    public function getBackgroundImages($hotel)
    {
        $imageUrl = $hotel->fotoBg;

        $imageTypes = ["small","medium","large"];
        $urls = [];

        foreach ($imageTypes as $imageType) {
            $url = str_replace(["original","small","medium","large"], $imageType, $imageUrl);
            $urls[$imageType] = $url;
        }

        return $urls;
    }

    public function convertToRGB($hex)
    {
        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        $color_parts = str_split($hex, 2);

        return [
            "R" => hexdec($color_parts[0]),
            "G" => hexdec($color_parts[1]),
            "B" => hexdec($color_parts[2]),
        ];
    }

    public function calcTextColor($hex)
    {
        $color_parts = $this->convertToRGB($hex);

        $r = $color_parts["R"] * 0.2126;
        $g = $color_parts["G"] * 0.7152;
        $b = $color_parts["B"] * 0.0722;

        $perceivedLightness = ($r + $g + $b) / 255;
        $threshold = 0.5;

        $lightness = ($perceivedLightness - $threshold) * -10000000;

        if ($lightness > 0) {
            return "#DCDCDC";
        } else {
            return "#4E4E4E";
        }
    }

    public function adjustBrightness($hex, $steps)
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        $color_parts = $this->convertToRGB($hex);
        $return = '#';

        foreach ($color_parts as $color) {
            $color = max(0, min(255, $color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }
}
