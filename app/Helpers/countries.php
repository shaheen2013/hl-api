<?php

use App\CountryLang;

function get_country_name($locale)
{
    if (empty($locale)) {
        return 'Unknown';
    }
    $locale = str_replace("_", "-", $locale);
    $cache_name = 'country_name_' . md5($locale);
    if ($name = cache($cache_name)) {
        return $name;
    }

    $country = CountryLang::whereRaw("FIND_IN_SET('{$locale}', `locale`) > 0")->first();

    if ($country_name = ($country->country ?? null)) {
        $country_name = trim($country_name);
        cache([$cache_name => $country_name], now()->addDays(365));
    }

    return $country_name ?? 'Unknown';
}
