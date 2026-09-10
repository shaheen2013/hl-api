<?php

namespace Tests\Unit;

use Tests\TestCase;

class CountriesHelperTest extends TestCase
{
    /**
     * Test that helper can fetch correcty countries
     */
    public function testCountriesRetrieveCorrectly()
    {
        $langsCountries = [
            'es' => 'Spain',
            'es-ES' => 'Spain',
            'es_ES' => 'Spain',
            'en-GB' => 'United Kingdom',
            'en-US' => 'United States of America',
            'pt' => 'Portugal',
            'pt-BR' => 'Brazil',
            null => 'Unknown',
            0 => 'Unknown',
        ];

        collect($langsCountries)->each(function ($expectedCountry, $lang) {
            $country = get_country_name($lang);
            $this->assertEquals($expectedCountry, $country);
        });
    }
}
