<?php

use Illuminate\Database\Seeder;
use App\TimeZone;

class GenesisTimeZoneTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        TimeZone::updateOrCreate(
            ['id' => 1],
            [
                'gmt'         => 'GMT-12:00',
                'time_zone'   => 'Pacific/Wake',
                'description' => 'International Date Line West'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 2],
            [
                'gmt'         => 'GMT-11:00',
                'time_zone'   => 'Pacific/Midway',
                'description' => 'Midway Island'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 3],
            [
                'gmt'         => 'GMT-11:00',
                'time_zone'   => 'Pacific/Apia',
                'description' => 'Apia, Samoa'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 4],
            [
                'gmt'         => 'GMT-10:00',
                'time_zone'   => 'Pacific/Honolulu',
                'description' => 'Honolulu'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 5],
            [
                'gmt'         => 'GMT-08:00',
                'time_zone'   => 'America/Anchorage',
                'description' => 'Anchorage'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 6],
            [
                'gmt'         => 'GMT-08:00',
                'time_zone'   => 'America/Los_Angeles',
                'description' => 'Pacific Time'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 7],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Phoenix',
                'description' => 'Arizona'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 8],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Chihuahua',
                'description' => 'Chihuahua'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 9],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Chihuahua',
                'description' => 'La Paz Chihuahua'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 10],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Mazatlan',
                'description' => 'Mazatlan'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 11],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Denver',
                'description' => 'Denver'
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 12],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Managua',
                'description' => 'Managua'
            ]

        );

        TimeZone::updateOrCreate(
            ['id' => 13],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Chicago',
                'description' => 'Central Time'
            ]

        );

        TimeZone::updateOrCreate(
            ['id' => 14],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Mexico_City',
                'description' => 'Guadalajara',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 15],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Mexico_City',
                'description' => 'Mexico City',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 16],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Monterrey',
                'description' => 'Monterrey',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 17],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Regina',
                'description' => 'Saskatchewan',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 18],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Bogota',
                'description' => 'Bogota',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 19],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/New_York',
                'description' => 'Eastern Time',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 20],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Indiana/Indianapolis',
                'description' => 'Indianapolis',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 21],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Lima',
                'description' => 'Lima',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 22],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Bogota',
                'description' => 'Quito',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 23],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Halifax',
                'description' => 'Atlantic Time',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 24],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Caracas',
                'description' => 'Caracas',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 25],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/La_Paz',
                'description' => 'La Paz',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 26],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Santiago',
                'description' => 'Santiago',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 27],
            [
                'gmt'         => 'GMT-03:30',
                'time_zone'   => 'America/St_Johns',
                'description' => 'St. Johns',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 28],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Sao_Paulo',
                'description' => 'Brasilia',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 29],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/Buenos_Aires',
                'description' => 'Buenos Aires',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 30],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Guyana',
                'description' => 'Georgetown',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 31],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Godthab',
                'description' => 'Nuuk (Godthåb), Greenland',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 32],
            [
                'gmt'         => 'GMT-02:00',
                'time_zone'   => 'America/Noronha',
                'description' => 'Mid-Atlantic',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 33],
            [
                'gmt'         => 'GMT-01:00',
                'time_zone'   => 'Atlantic/Azores',
                'description' => 'Azores',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 34],
            [
                'gmt'         => 'GMT-01:00',
                'time_zone'   => 'Atlantic/Cape_Verde',
                'description' => 'Cape Verde',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 35],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Casablanca',
                'description' => 'Casablanca',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 36],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Europe/London',
                'description' => 'Edinburgh',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 37],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Europe/Dublin',
                'description' => 'Dublin',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 38],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Europe/Lisbon',
                'description' => 'Lisbon',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 39],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Europe/London',
                'description' => 'London',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 40],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Monrovia',
                'description' => 'Monrovia',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 41],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Amsterdam',
                'description' => 'Amsterdam',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 42],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Belgrade',
                'description' => 'Belgrade',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 43],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Berlin',
                'description' => 'Berlin',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 44],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Zurich',
                'description' => 'Bern',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 45],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Bratislava',
                'description' => 'Bratislava',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 46],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Brussels',
                'description' => 'Brussels',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 47],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Budapest',
                'description' => 'Budapest',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 48],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Copenhagen',
                'description' => 'Copenhagen',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 49],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Ljubljana',
                'description' => 'Ljubljana',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 50],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Madrid',
                'description' => 'Madrid',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 51],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Paris',
                'description' => 'Paris',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 52],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Prague',
                'description' => 'Prague',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 53],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Rome',
                'description' => 'Rome',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 54],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Sarajevo',
                'description' => 'Sarajevo',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 55],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Skopje',
                'description' => 'Skopje',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 56],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Stockholm',
                'description' => 'Stockholm',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 57],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Vienna',
                'description' => 'Vienna',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 58],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Warsaw',
                'description' => 'Warsaw',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 59],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Lagos',
                'description' => 'West Central Africa',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 60],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Zagreb',
                'description' => 'Zagreb',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 61],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Athens',
                'description' => 'Athens',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 62],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Bucharest',
                'description' => 'Bucharest',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 63],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Cairo',
                'description' => 'Cairo',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 64],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Harare',
                'description' => 'Harare',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 65],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Helsinki',
                'description' => 'Helsinki',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 66],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Istanbul',
                'description' => 'Istanbul',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 67],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Asia/Jerusalem',
                'description' => 'Jerusalem',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 68],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Kiev',
                'description' => 'Kyiv',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 69],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Minsk',
                'description' => 'Minsk',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 70],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Johannesburg',
                'description' => 'Johannesburg',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 71],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Riga',
                'description' => 'Riga',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 72],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Sofia',
                'description' => 'Sofia',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 73],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Tallinn',
                'description' => 'Tallinn',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 74],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Vilnius',
                'description' => 'Vilnius',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 75],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Asia/Baghdad',
                'description' => 'Baghdad',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 76],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Asia/Kuwait',
                'description' => 'Kuwait',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 77],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Europe/Moscow',
                'description' => 'Moscow',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 78],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Africa/Nairobi',
                'description' => 'Nairobi',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 79],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Asia/Riyadh',
                'description' => 'Riyadh',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 80],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Europe/Moscow',
                'description' => 'St. Petersburg',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 81],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Europe/Volgograd',
                'description' => 'Volgograd',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 82],
            [
                'gmt'         => 'GMT+03:30',
                'time_zone'   => 'Asia/Tehran',
                'description' => 'Tehran',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 83],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Asia/Dubai',
                'description' => 'Abu Dhabi',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 84],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Asia/Baku',
                'description' => 'Baku',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 85],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Asia/Muscat',
                'description' => 'Muscat',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 86],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Asia/Tbilisi',
                'description' => 'Tbilisi',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 87],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Asia/Yerevan',
                'description' => 'Yerevan',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 88],
            [
                'gmt'         => 'GMT+04:30',
                'time_zone'   => 'Asia/Kabul',
                'description' => 'Kabul',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 89],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Yekaterinburg',
                'description' => 'Yekaterinburg',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 90],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Karachi',
                'description' => 'Islamabad',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 91],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Karachi',
                'description' => 'Karachi',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 92],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Tashkent',
                'description' => 'Tashkent',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 93],
            [
                'gmt'         => 'GMT+05:30',
                'time_zone'   => 'Asia/Calcutta',
                'description' => 'Chennai',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 94],
            [
                'gmt'         => 'GMT+05:30',
                'time_zone'   => 'Asia/Calcutta',
                'description' => 'Kolkata',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 95],
            [
                'gmt'         => 'GMT+05:30',
                'time_zone'   => 'Asia/Calcutta',
                'description' => 'Mumbai',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 96],
            [
                'gmt'         => 'GMT+05:30',
                'time_zone'   => 'Asia/Calcutta',
                'description' => 'New Delhi',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 97],
            [
                'gmt'         => 'GMT+05:45',
                'time_zone'   => 'Asia/Katmandu',
                'description' => 'Kathmandu',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 98],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Asia/Almaty',
                'description' => 'Almaty',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 99],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Asia/Almaty',
                'description' => 'Astana',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 100],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Asia/Dhaka',
                'description' => 'Dhaka',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 101],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Asia/Novosibirsk',
                'description' => 'Novosibirsk',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 102],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Asia/Colombo',
                'description' => 'Colombo, Sri Lanka',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 103],
            [
                'gmt'         => 'GMT+06:30',
                'time_zone'   => 'Asia/Rangoon',
                'description' => 'Yangon (Rangoon)',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 104],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Bangkok',
                'description' => 'Bangkok',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 105],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Saigon',
                'description' => 'Hanoi',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 106],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Jakarta',
                'description' => 'Jakarta',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 107],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Krasnoyarsk',
                'description' => 'Krasnoyarsk',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 108],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Shanghai',
                'description' => 'Beijing',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 109],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Shanghai',
                'description' => 'Chongqing',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 110],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Hong_Kong',
                'description' => 'Hong Kong',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 111],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Irkutsk',
                'description' => 'Irkutsk',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 112],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Kuala_Lumpur',
                'description' => 'Kuala Lumpur',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 113],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Australia/Perth',
                'description' => 'Perth',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 114],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Singapore',
                'description' => 'Singapore',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 115],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Taipei',
                'description' => 'Taipei',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 116],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Ulaanbaatar',
                'description' => 'Ulaanbaatar',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 117],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Urumqi',
                'description' => 'Urumqi',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 118],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Tokyo',
                'description' => 'Osaka',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 119],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Tokyo',
                'description' => 'Sapporo',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 120],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Seoul',
                'description' => 'Seoul',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 121],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Tokyo',
                'description' => 'Tokyo',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 122],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Yakutsk',
                'description' => 'Yakutsk',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 123],
            [
                'gmt'         => 'GMT+09:30',
                'time_zone'   => 'Australia/Adelaide',
                'description' => 'Adelaide',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 124],
            [
                'gmt'         => 'GMT+09:30',
                'time_zone'   => 'Australia/Darwin',
                'description' => 'Darwin',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 125],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Australia/Brisbane',
                'description' => 'Brisbane',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 126],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Australia/Sydney',
                'description' => 'Canberra',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 127],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Pacific/Guam',
                'description' => 'Guam',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 128],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Australia/Hobart',
                'description' => 'Hobart',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 129],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Australia/Melbourne',
                'description' => 'Melbourne',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 130],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Pacific/Port_Moresby',
                'description' => 'Port Moresby',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 131],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Australia/Sydney',
                'description' => 'Sydney',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 132],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Asia/Vladivostok',
                'description' => 'Vladivostok',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 133],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Asia/Magadan',
                'description' => 'Magadan',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 134],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Pacific/Noumea',
                'description' => 'Noumea, New Caledonia',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 135],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Pacific/Guadalcanal',
                'description' => 'Guadalcanal, Solomon Islands',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 136],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Pacific/Auckland',
                'description' => 'Auckland',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 137],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Pacific/Fiji',
                'description' => 'Fiji',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 138],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Pacific/Kwajalein',
                'description' => 'Kwajalein, Marshall Islands',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 139],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Pacific/Majuro',
                'description' => 'Majuro, Marshall Islands',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 140],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Pacific/Chuuk',
                'description' => 'Chuuk',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 141],
            [
                'gmt'         => 'GMT+13:00',
                'time_zone'   => 'Pacific/Tongatapu',
                'description' => 'Tongatapu',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 142],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Abidjan',
                'description' => 'Abidjan',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 143],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Africa/Asmara',
                'description' => 'Asmara',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 144],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Bissau',
                'description' => 'Bissau',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 145],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Dakar',
                'description' => 'Dakar',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 146],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/El_Aaiun',
                'description' => 'El Aaiun',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 147],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Kigali',
                'description' => 'Kigali',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 148],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Lome',
                'description' => 'Lome',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 149],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Malabo',
                'description' => 'Malabo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 150],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Africa/Mogadishu',
                'description' => 'Mogadishu',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 151],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Niamey',
                'description' => 'Niamey',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 152],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Sao_Tome',
                'description' => 'Sao_Tome',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 153],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Accra',
                'description' => 'Accra',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 154],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Bamako',
                'description' => 'Bamako',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 155],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Blantyre',
                'description' => 'Blantyre',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 156],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Africa/Dar_es_Salaam',
                'description' => 'Dar es Salaam',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 157],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Freetown',
                'description' => 'Freetown',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 158],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Juba',
                'description' => 'Juba',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 159],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Kinshasa',
                'description' => 'Kinshasa',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 160],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Luanda',
                'description' => 'Luanda',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 161],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Maputo',
                'description' => 'Maputo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 162],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Nouakchott',
                'description' => 'Nouakchott',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 163],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Tripoli',
                'description' => 'Tripoli',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 164],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Africa/Addis_Ababa',
                'description' => 'Addis Ababa',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 165],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Bangui',
                'description' => 'Bangui',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 166],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Brazzaville',
                'description' => 'Brazzaville',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 167],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Ceuta',
                'description' => 'Ceuta',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 168],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Africa/Djibouti',
                'description' => 'Djibouti',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 169],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Gaborone',
                'description' => 'Gaborone',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 170],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Africa/Kampala',
                'description' => 'Kampala',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 171],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Lubumbashi',
                'description' => 'Lubumbashi',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 172],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Maseru',
                'description' => 'Maseru',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 173],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Ouagadougou',
                'description' => 'Ouagadougou',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 174],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Tunis',
                'description' => 'Tunis',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 175],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Algiers',
                'description' => 'Algiers',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 176],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Banjul',
                'description' => 'Banjul',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 177],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Bujumbura',
                'description' => 'Bujumbura',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 178],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Africa/Conakry',
                'description' => 'Conakry',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 179],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Douala',
                'description' => 'Douala',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 180],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Khartoum',
                'description' => 'Khartoum',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 181],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Libreville',
                'description' => 'Libreville',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 182],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Lusaka',
                'description' => 'Lusaka',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 183],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Mbabane',
                'description' => 'Mbabane',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 184],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Ndjamena',
                'description' => 'Ndjamena',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 185],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Africa/Porto-Novo',
                'description' => 'Porto-Novo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 186],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Africa/Windhoek',
                'description' => 'Windhoek',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 187],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Kaliningrad',
                'description' => 'Kaliningrad',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 188],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Malta',
                'description' => 'Malta',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 189],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/San_Marino',
                'description' => 'San Marino',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 190],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Tirane',
                'description' => 'Tirane',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 191],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Vatican',
                'description' => 'Vatican',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 192],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Andorra',
                'description' => 'Andorra',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 193],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Europe/Isle_of_Man',
                'description' => 'Isle of Man',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 194],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Mariehamn',
                'description' => 'Mariehamn',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 195],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Oslo',
                'description' => 'Oslo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 196],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Europe/Ulyanovsk',
                'description' => 'Ulyanovsk',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 197],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Europe/Astrakhan',
                'description' => 'Astrakhan',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 198],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Busingen',
                'description' => 'Busingen',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 199],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Gibraltar',
                'description' => 'Gibraltar',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 200],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Europe/Kirov',
                'description' => 'Kirov',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 201],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Luxembourg',
                'description' => 'Luxembourg',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 202],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Europe/Saratov',
                'description' => 'Saratov',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 203],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Uzhgorod',
                'description' => 'Uzhgorod',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 204],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Zaporozhye',
                'description' => 'Zaporozhye',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 205],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Europe/Chisinau',
                'description' => 'Chisinau',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 206],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Europe/Guernsey',
                'description' => 'Guernsey',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 207],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Europe/Jersey',
                'description' => 'Jersey',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 208],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Monaco',
                'description' => 'Monaco',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 209],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Podgorica',
                'description' => 'Podgorica',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 210],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Europe/Samara',
                'description' => 'Samara',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 211],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Europe/Simferopol',
                'description' => 'Simferopol',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 212],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Europe/Vaduz',
                'description' => 'Vaduz',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 213],
            [
                'gmt'         => 'GMT-09:00',
                'time_zone'   => 'America/Adak',
                'description' => 'Adak',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 214],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Araguaina',
                'description' => 'Araguaina',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 215],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/Jujuy',
                'description' => 'Jujuy',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 216],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/Salta',
                'description' => 'Salta',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 217],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/Ushuaia',
                'description' => 'Ushuaia',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 218],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Bahia',
                'description' => 'Bahia',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 219],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Belize',
                'description' => 'Belize',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 220],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Boise',
                'description' => 'Boise',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 221],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Curacao',
                'description' => 'Curacao',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 222],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Eirunepe',
                'description' => 'Eirunepe',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 223],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Glace_Bay',
                'description' => 'Glace_Bay',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 224],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Grenada',
                'description' => 'Grenada',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 225],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Indiana/Tell_City',
                'description' => 'Tell City',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 226],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Inuvik',
                'description' => 'Inuvik',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 227],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Kentucky/Louisville',
                'description' => 'Louisville',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 228],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Matamoros',
                'description' => 'Matamoros',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 229],
            [
                'gmt'         => 'GMT-08:00',
                'time_zone'   => 'America/Metlakatla',
                'description' => 'Metlakatla',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 230],
            [
                'gmt'         => 'GMT-09:00',
                'time_zone'   => 'Pacific/Gambier',
                'description' => 'Gambier',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 231],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/North_Dakota/Beulah',
                'description' => 'Beulah',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 232],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Panama',
                'description' => 'Panama',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 233],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Port-au-Prince',
                'description' => 'Port au Prince',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 234],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Punta_Arenas',
                'description' => 'Punta Arenas',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 235],
            [
                'gmt'         => 'GMT-08:00',
                'time_zone'   => 'America/Sitka',
                'description' => 'Sitka',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 236],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/St_Lucia',
                'description' => 'St Lucia',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 237],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Tegucigalpa',
                'description' => 'Tegucigalpa',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 238],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Toronto',
                'description' => 'Toronto',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 239],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Winnipeg',
                'description' => 'Winnipeg',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 240],
            [
                'gmt'         => 'GMT+13:00',
                'time_zone'   => 'Pacific/Fakaofo',
                'description' => 'Fakaofo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 241],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/La_Rioja',
                'description' => 'La_Rioja',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 242],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/San_Juan',
                'description' => 'San_Juan',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 243],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Aruba',
                'description' => 'Aruba',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 244],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Bahia_Banderas',
                'description' => 'Bahia_Banderas',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 245],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Blanc-Sablon',
                'description' => 'Blanc Sablon',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 246],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Cambridge_Bay',
                'description' => 'Cambridge_Bay',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 247],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Cayenne',
                'description' => 'Cayenne',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 248],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Costa_Rica',
                'description' => 'Costa_Rica',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 249],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'America/Danmarkshavn',
                'description' => 'Danmarkshavn',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 250],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Detroit',
                'description' => 'Detroit',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 251],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/El_Salvador',
                'description' => 'El Salvador',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 252],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Guadeloupe',
                'description' => 'Guadeloupe',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 253],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Indiana/Knox',
                'description' => 'Knox',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 254],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Indiana/Vevay',
                'description' => 'Vevay',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 255],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Iqaluit',
                'description' => 'Iqaluit',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 256],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Kentucky/Monticello',
                'description' => 'Monticello',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 257],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Manaus',
                'description' => 'Manaus',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 258],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Montevideo',
                'description' => 'Montevideo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 259],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Nipigon',
                'description' => 'Nipigon',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 260],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/North_Dakota/Center',
                'description' => 'Center',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 261],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Pangnirtung',
                'description' => 'Pangnirtung',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 262],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Port_of_Spain',
                'description' => 'Port_of_Spain',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 263],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Rainy_River',
                'description' => 'Rainy_River',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 264],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Resolute',
                'description' => 'Resolute',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 265],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Santo_Domingo',
                'description' => 'Santo Domingo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 266],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/St_Barthelemy',
                'description' => 'St Barthelemy',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 267],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/St_Thomas',
                'description' => 'St_Thomas',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 268],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Thule',
                'description' => 'Thule',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 269],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Tortola',
                'description' => 'Tortola',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 270],
            [
                'gmt'         => 'GMT-08:00',
                'time_zone'   => 'America/Yakutat',
                'description' => 'Yakutat',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 271],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Anguilla',
                'description' => 'Anguilla',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 272],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/Catamarca',
                'description' => 'Catamarca',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 273],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/Mendoza',
                'description' => 'Mendoza',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 274],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/San_Luis',
                'description' => 'San_Luis',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 275],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Asuncion',
                'description' => 'Asuncion',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 276],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Barbados',
                'description' => 'Barbados',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 277],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Boa_Vista',
                'description' => 'Boa Vista',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 278],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Campo_Grande',
                'description' => 'Campo Grande',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 279],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Cayman',
                'description' => 'Cayman',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 280],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Creston',
                'description' => 'Creston',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 281],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Dawson',
                'description' => 'Dawson',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 282],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Dominica',
                'description' => 'Dominica',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 283],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Fort_Nelson',
                'description' => 'Fort_Nelson',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 284],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Goose_Bay',
                'description' => 'Goose_Bay',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 285],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Guatemala',
                'description' => 'Guatemala',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 286],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Havana',
                'description' => 'Havana',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 287],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Indiana/Marengo',
                'description' => 'Marengo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 288],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Indiana/Vincennes',
                'description' => 'Vincennes',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 289],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Jamaica',
                'description' => 'Jamaica',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 290],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Kralendijk',
                'description' => 'Kralendijk',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 291],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Lower_Princes',
                'description' => 'Lower_Princes',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 292],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Marigot',
                'description' => 'Marigot',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 293],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Menominee',
                'description' => 'Menominee',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 294],
            [
                'gmt'         => 'GMT-02:00',
                'time_zone'   => 'America/Miquelon',
                'description' => 'Miquelon',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 295],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Montserrat',
                'description' => 'Montserrat',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 296],
            [
                'gmt'         => 'GMT-08:00',
                'time_zone'   => 'America/Nome',
                'description' => 'Nome',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 297],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/North_Dakota/New_Salem',
                'description' => 'New Salem',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 298],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Paramaribo',
                'description' => 'Paramaribo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 299],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Porto_Velho',
                'description' => 'Porto Velho',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 300],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Rankin_Inlet',
                'description' => 'Rankin Inlet',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 301],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Rio_Branco',
                'description' => 'Rio Branco',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 302],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/St_Vincent',
                'description' => 'St Vincent',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 303],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Thunder_Bay',
                'description' => 'Thunder Bay',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 304],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Vancouver',
                'description' => 'Vancouver',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 305],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Yellowknife',
                'description' => 'Yellowknife',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 306],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Antigua',
                'description' => 'Antigua',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 307],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/Cordoba',
                'description' => 'Cordoba',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 308],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/Rio_Gallegos',
                'description' => 'Rio Gallegos',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 309],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Argentina/Tucuman',
                'description' => 'Tucuman',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 310],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Atikokan',
                'description' => 'Atikokan',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 311],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Belem',
                'description' => 'Belem',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 312],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Cancun',
                'description' => 'Cancun',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 313],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Cuiaba',
                'description' => 'Cuiaba',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 314],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Dawson_Creek',
                'description' => 'Dawson Creek',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 315],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Edmonton',
                'description' => 'Edmonton',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 316],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Fortaleza',
                'description' => 'Fortaleza',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 317],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Grand_Turk',
                'description' => 'Grand_Turk',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 318],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Guayaquil',
                'description' => 'Guayaquil',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 319],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Hermosillo',
                'description' => 'Hermosillo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 320],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Indiana/Petersburg',
                'description' => 'Petersburg',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 321],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Indiana/Winamac',
                'description' => 'Winamac',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 322],
            [
                'gmt'         => 'GMT-08:00',
                'time_zone'   => 'America/Juneau',
                'description' => 'Juneau',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 323],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Maceio',
                'description' => 'Maceio',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 324],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Martinique',
                'description' => 'Martinique',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 325],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Merida',
                'description' => 'Merida',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 326],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Moncton',
                'description' => 'Moncton',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 327],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Nassau',
                'description' => 'Nassau',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 328],
            [
                'gmt'         => 'GMT-05:00',
                'time_zone'   => 'America/Ojinaga',
                'description' => 'Ojinaga',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 329],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/Puerto_Rico',
                'description' => 'Puerto_Rico',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 330],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Recife',
                'description' => 'Recife',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 331],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'America/Santarem',
                'description' => 'Santarem',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 332],
            [
                'gmt'         => 'GMT-01:00',
                'time_zone'   => 'America/Scoresbysund',
                'description' => 'Scoresbysund',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 333],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'America/St_Kitts',
                'description' => 'St Kitts',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 334],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'America/Swift_Current',
                'description' => 'Swift Current',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 335],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Tijuana',
                'description' => 'Tijuana',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 336],
            [
                'gmt'         => 'GMT-07:00',
                'time_zone'   => 'America/Whitehorse',
                'description' => 'Whitehorse',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 337],
            [
                'gmt'         => 'GMT+14:00',
                'time_zone'   => 'Pacific/Kiritimati',
                'description' => 'Kiritimati',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 338],
            [
                'gmt'         => 'GMT-9:30',
                'time_zone'   => 'Pacific/Marquesas',
                'description' => 'Marquesas',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 339],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Pacific/Norfolk',
                'description' => 'Norfolk',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 340],
            [
                'gmt'         => 'GMT-08:00',
                'time_zone'   => 'Pacific/Pitcairn',
                'description' => 'Pitcairn',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 341],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Pacific/Saipan',
                'description' => 'Saipan',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 342],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'Pacific/Easter',
                'description' => 'Easter',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 343],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Pacific/Kosrae',
                'description' => 'Kosrae',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 344],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Pacific/Pohnpei',
                'description' => 'Pohnpei',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 345],
            [
                'gmt'         => 'GMT-10:00',
                'time_zone'   => 'Pacific/Tahiti',
                'description' => 'Tahiti',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 346],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Pacific/Wallis',
                'description' => 'Wallis',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 347],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Pacific/Bougainville',
                'description' => 'Bougainville',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 348],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Pacific/Efate',
                'description' => 'Efate',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 349],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Pacific/Funafuti',
                'description' => 'Funafuti',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 350],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Pacific/Nauru',
                'description' => 'Nauru',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 351],
            [
                'gmt'         => 'GMT-11:00',
                'time_zone'   => 'Pacific/Pago_Pago',
                'description' => 'Pago Pago',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 352],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Pacific/Tarawa',
                'description' => 'Tarawa',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 353],
            [
                'gmt'         => 'GMT+12:45',
                'time_zone'   => 'Pacific/Chatham',
                'description' => 'Chatham',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 354],
            [
                'gmt'         => 'GMT+13:00',
                'time_zone'   => 'Pacific/Enderbury',
                'description' => 'Enderbury',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 355],
            [
                'gmt'         => 'GMT-06:00',
                'time_zone'   => 'Pacific/Galapagos',
                'description' => 'Galapagos',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 356],
            [
                'gmt'         => 'GMT-11:00',
                'time_zone'   => 'Pacific/Niue',
                'description' => 'Niue',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 357],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Pacific/Palau',
                'description' => 'Palau',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 358],
            [
                'gmt'         => 'GMT-10:00',
                'time_zone'   => 'Pacific/Rarotonga',
                'description' => 'Rarotonga',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 359],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Indian/Antananarivo',
                'description' => 'Antananarivo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 360],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Indian/Comoro',
                'description' => 'Comoro',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 361],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Indian/Mauritius',
                'description' => 'Mauritius',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 362],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Indian/Chagos',
                'description' => 'Chagos',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 363],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Indian/Kerguelen',
                'description' => 'Kerguelen',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 364],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Indian/Mayotte',
                'description' => 'Mayotte',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 365],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Indian/Christmas',
                'description' => 'Christmas',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 366],
            [
                'gmt'         => 'GMT+05:30',
                'time_zone'   => 'Indian/Mahe',
                'description' => 'Mahe',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 367],
            [
                'gmt'         => 'GMT+04:00',
                'time_zone'   => 'Indian/Reunion',
                'description' => 'Reunion',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 368],
            [
                'gmt'         => 'GMT+06:30',
                'time_zone'   => 'Indian/Cocos',
                'description' => 'Cocos',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 369],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Indian/Maldives',
                'description' => 'Maldives',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 370],
            [
                'gmt'         => 'GMT+13:00',
                'time_zone'   => 'Antarctica/Casey',
                'description' => 'Casey',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 371],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Antarctica/Mawson',
                'description' => 'Mawson',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 372],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Antarctica/Syowa',
                'description' => 'Syowa',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 373],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Antarctica/Davis',
                'description' => 'Davis',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 374],
            [
                'gmt'         => 'GMT+13:00',
                'time_zone'   => 'Antarctica/McMurdo',
                'description' => 'McMurdo',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 375],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Antarctica/Troll',
                'description' => 'Troll',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 376],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Antarctica/DumontDUrville',
                'description' => 'DumontDUrville',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 377],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'Antarctica/Palmer',
                'description' => 'Palmer',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 378],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Antarctica/Vostok',
                'description' => 'Vostok',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 379],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Antarctica/Macquarie',
                'description' => 'Macquarie',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 380],
            [
                'gmt'         => 'GMT+13:00',
                'time_zone'   => 'Antarctica/Rothera',
                'description' => 'Rothera',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 381],
            [
                'gmt'         => 'GMT+01:00',
                'time_zone'   => 'Arctic/Longyearbyen',
                'description' => 'Longyearbyen',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 382],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Australia/Lord_Howe',
                'description' => 'Lord_Howe',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 383],
            [
                'gmt'         => 'GMT+08:45',
                'time_zone'   => 'Australia/Eucla',
                'description' => 'Eucla',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 384],
            [
                'gmt'         => 'GMT+10:30',
                'time_zone'   => 'Australia/Broken_Hill',
                'description' => 'Broken_Hill',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 385],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Australia/Currie',
                'description' => 'Currie',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 386],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Australia/Lindeman',
                'description' => 'Lindeman',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 387],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Atlantic/Faroe',
                'description' => 'Faroe',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 388],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Atlantic/St_Helena',
                'description' => 'St_Helena',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 389],
            [
                'gmt'         => 'GMT-04:00',
                'time_zone'   => 'Atlantic/Bermuda',
                'description' => 'Bermuda',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 390],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Atlantic/Madeira',
                'description' => 'Madeira',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 391],
            [
                'gmt'         => 'GMT-03:00',
                'time_zone'   => 'Atlantic/Stanley',
                'description' => 'Stanley',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 392],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Atlantic/Canary',
                'description' => 'Canary',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 393],
            [
                'gmt'         => 'GMT',
                'time_zone'   => 'Atlantic/Reykjavik',
                'description' => 'Reykjavik',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 394],
            [
                'gmt'         => 'GMT-02:00',
                'time_zone'   => 'Atlantic/South_Georgia',
                'description' => 'South_Georgia',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 395],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Asia/Aden',
                'description' => 'Aden',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 396],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Aqtau',
                'description' => 'Aqtau',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 397],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Barnaul',
                'description' => 'Barnaul',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 398],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Chita',
                'description' => 'Chita',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 399],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Asia/Famagusta',
                'description' => 'Famagusta',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 400],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Jayapura',
                'description' => 'Jayapura',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 401],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Macau',
                'description' => 'Macau',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 402],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Asia/Omsk',
                'description' => 'Omsk',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 403],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Pyongyang',
                'description' => 'Pyongyang',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 404],
            [
                'gmt'         => 'GMT+10:00',
                'time_zone'   => 'Asia/Ust-Nera',
                'description' => 'Ust-Nera',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 405],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Aqtobe',
                'description' => 'Aqtobe',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 406],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Asia/Bahrain',
                'description' => 'Bahrain',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 407],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Asia/Beirut',
                'description' => 'Beirut',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 408],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Choibalsan',
                'description' => 'Choibalsan',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 409],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Dili',
                'description' => 'Dili',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 410],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Asia/Gaza',
                'description' => 'Gaza',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 411],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Hovd',
                'description' => 'Hovd',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 412],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Asia/Nicosia',
                'description' => 'Nicosia',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 413],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Oral',
                'description' => 'Oral',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 414],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Asia/Qatar',
                'description' => 'Qatar',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 415],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Asia/Sakhalin',
                'description' => 'Sakhalin',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 416],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Tomsk',
                'description' => 'Tomsk',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 417],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Vientiane',
                'description' => 'Vientiane',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 418],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Asia/Amman',
                'description' => 'Amman',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 419],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Ashgabat',
                'description' => 'Ashgabat',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 420],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Asia/Bishkek',
                'description' => 'Bishkek',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 421],
            [
                'gmt'         => 'GMT+02:00',
                'time_zone'   => 'Asia/Hebron',
                'description' => 'Hebron',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 422],
            [
                'gmt'         => 'GMT+09:00',
                'time_zone'   => 'Asia/Khandyga',
                'description' => 'Khandyga',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 423],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Kuching',
                'description' => 'Kuching',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 424],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Makassar',
                'description' => 'Makassar',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 425],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Novokuznetsk',
                'description' => 'Novokuznetsk',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 426],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Phnom_Penh',
                'description' => 'Phnom Penh',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 427],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Qostanay',
                'description' => 'Qostanay',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 428],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Samarkand',
                'description' => 'Samarkand',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 429],
            [
                'gmt'         => 'GMT+11:00',
                'time_zone'   => 'Asia/Srednekolymsk',
                'description' => 'Srednekolymsk',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 430],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Asia/Anadyr',
                'description' => 'Anadyr',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 431],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Atyrau',
                'description' => 'Atyrau',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 432],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Brunei',
                'description' => 'Brunei',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 433],
            [
                'gmt'         => 'GMT+03:00',
                'time_zone'   => 'Asia/Damascus',
                'description' => 'Damascus',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 434],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Dushanbe',
                'description' => 'Dushanbe',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 435],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Ho_Chi_Minh',
                'description' => 'Ho_Chi_Minh',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 436],
            [
                'gmt'         => 'GMT+12:00',
                'time_zone'   => 'Asia/Kamchatka',
                'description' => 'Kamchatka',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 437],
            [
                'gmt'         => 'GMT+08:00',
                'time_zone'   => 'Asia/Manila',
                'description' => 'Manila',
            ]
        );

        TimeZone::updateOrCreate(
            ['id' => 438],
            [
                'gmt'         => 'GMT+07:00',
                'time_zone'   => 'Asia/Pontianak',
                'description' => 'Pontianak',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 439],
            [
                'gmt'         => 'GMT+05:00',
                'time_zone'   => 'Asia/Qyzylorda',
                'description' => 'Qyzylorda',
            ]
        );
        TimeZone::updateOrCreate(
            ['id' => 440],
            [
                'gmt'         => 'GMT+06:00',
                'time_zone'   => 'Asia/Thimphu',
                'description' => 'Thimphu',
            ]
        );
    }
}
