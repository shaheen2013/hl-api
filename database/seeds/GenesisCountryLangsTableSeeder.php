<?php

use Illuminate\Database\Seeder;
use App\CountryLang;

class GenesisCountryLangsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        CountryLang::updateOrCreate(
            ['id' => 1],
            [
                'locale'  => 'NULL',
                'country' => 'none'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 3],
            [
                'locale'  => 'fa-AF,uz-AF,prs-AF,ps-AF',
                'country' => 'Afghanistan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 4],
            [
                'locale'  => 'sq,sq-AL',
                'country' => 'Albania'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 5],
            [
                'locale'  => 'ar-DZ,tzm-Latn-DZ',
                'country' => 'Algeria'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 6],
            [
                'locale'  => 'en-AS',
                'country' => 'American Samoa'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 7],
            [
                'locale'  => 'NULL',
                'country' => 'Andorra'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 8],
            [
                'locale'  => 'pt-AO',
                'country' => 'Angola'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 9],
            [
                'locale'  => 'en-AI',
                'country' => 'Anguilla'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 11],
            [
                'locale'  => 'en-AG',
                'country' => 'Antigua and Barbuda'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 12],
            [
                'locale'  => 'es-AR,ar_AR,ar',
                'country' => 'Argentina'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 13],
            [
                'locale'  => 'hy,hy-AM',
                'country' => 'Armenia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 14],
            [
                'locale'  => 'nl-AW',
                'country' => 'Aruba'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 15],
            [
                'locale'  => 'en-AU',
                'country' => 'Australia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 16],
            [
                'locale'  => 'de-AT',
                'country' => 'Austria'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 17],
            [
                'locale'  => 'az,az-Cyrl-AZ,az-Latn-AZ',
                'country' => 'Azerbaijan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 18],
            [
                'locale'  => 'en-BS',
                'country' => 'Bahamas'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 19],
            [
                'locale'  => 'ar-BH',
                'country' => 'Bahrain'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 20],
            [
                'locale'  => 'bn-BD',
                'country' => 'Bangladesh'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 21],
            [
                'locale'  => 'en-BB',
                'country' => 'Barbados'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 22],
            [
                'locale'  => 'be,be-BY',
                'country' => 'Belarus'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 23],
            [
                'locale'  => 'nl-BE,fr-BE,de-BE',
                'country' => 'Belgium'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 24],
            [
                'locale'  => 'en-BZ',
                'country' => 'Belize'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 25],
            [
                'locale'  => 'fr-BJ',
                'country' => 'Benin'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 26],
            [
                'locale'  => 'en-BM',
                'country' => 'Bermuda'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 27],
            [
                'locale'  => 'dz',
                'country' => 'Bhutan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 28],
            [
                'locale'  => 'es-BO,quz-BO',
                'country' => 'Bolivia (Plurinational State of)'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 29],
            [
                'locale'  => 'bs,hr-BA,bs-Cyrl-BA,bs-Latn-BA',
                'country' => 'Bosnia and Herzegovina'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 30],
            [
                'locale'  => 'en-BW,tn-BW',
                'country' => 'Botswana'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 32],
            [
                'locale'  => 'pt-BR,pt_BR',
                'country' => 'Brazil'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 34],
            [
                'locale'  => 'en-VG',
                'country' => 'British Virgin Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 35],
            [
                'locale'  => 'en-BN,ms-BN',
                'country' => 'Brunei Darussalam'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 36],
            [
                'locale'  => 'bg,bg-BG',
                'country' => 'Bulgaria'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 37],
            [
                'locale'  => 'fr-BF',
                'country' => 'Burkina Faso'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 38],
            [
                'locale'  => 'fr-BI',
                'country' => 'Burundi'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 39],
            [
                'locale'  => 'km,km-KH',
                'country' => 'Cambodia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 40],
            [
                'locale'  => 'fr-CM',
                'country' => 'Cameroon'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 41],
            [
                'locale'  => 'en-CA,fr-CA,iu-Cans-CA,iu-Latn-CA,moh-CA',
                'country' => 'Canada'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 42],
            [
                'locale'  => 'pt-CV',
                'country' => 'Cabo Verde'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 43],
            [
                'locale'  => 'pap',
                'country' => 'Bonaire, Sint Eustatius and Saba'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 44],
            [
                'locale'  => 'en-KY',
                'country' => 'Cayman Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 45],
            [
                'locale'  => 'fr-CF',
                'country' => 'Central African Republic'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 46],
            [
                'locale'  => 'fr-TD',
                'country' => 'Chad'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 47],
            [
                'locale'  => 'es-CL,arn-CL',
                'country' => 'Chile'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 48],
            [
                'locale'  => 'zh-CN,bo-CN,ii-CN,mn-Mong-CN,ug-CN,zh-TW,zh_HANS,zh_HANT,z,yue_HANT',
                'country' => 'China'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 51],
            [
                'locale'  => 'es-CO',
                'country' => 'Colombia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 52],
            [
                'locale'  => 'fr-KM',
                'country' => 'Comoros'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 53],
            [
                'locale'  => 'fr-CG',
                'country' => 'Congo'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 54],
            [
                'locale'  => 'fr-CD,sr-Cyrl-CS,sr-Latn-CS',
                'country' => 'Democratic Republic of the Congo'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 55],
            [
                'locale'  => 'en-CK',
                'country' => 'Cook Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 56],
            [
                'locale'  => 'es-CR',
                'country' => 'Costa Rica'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 57],
            [
                'locale'  => 'hr-HR',
                'country' => 'Croatia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 58],
            [
                'locale'  => 'es-CU',
                'country' => 'Cuba'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 59],
            [
                'locale'  => 'pap',
                'country' => 'CuraÃ§ao'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 60],
            [
                'locale'  => 'el-CY,tr-CY,el_CY,cy_GB,cy-GB,cy',
                'country' => 'Cyprus'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 61],
            [
                'locale'  => 'cs,sk,cs-CZ,sk-CZ,cs_CZ',
                'country' => 'Czechia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 62],
            [
                'locale'  => 'fr-CI',
                'country' => 'CÃ´te d\'Ivoire'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 63],
            [
                'locale'  => 'da,da-DK,de-DK,da_DK,da-dk,dk',
                'country' => 'Denmark'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 64],
            [
                'locale'  => 'fr-DJ,so-DJ',
                'country' => 'Djibouti'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 65],
            [
                'locale'  => 'en-DM',
                'country' => 'Dominica'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 66],
            [
                'locale'  => 'es-DO',
                'country' => 'Dominican Republic'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 67],
            [
                'locale'  => 'es-EC,quz-EC',
                'country' => 'Ecuador'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 68],
            [
                'locale'  => 'ar-EG',
                'country' => 'Egypt'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 69],
            [
                'locale'  => 'es-SV',
                'country' => 'El Salvador'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 70],
            [
                'locale'  => 'es-GQ',
                'country' => 'Equatorial Guinea'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 71],
            [
                'locale'  => 'aa-ER,ti-ER',
                'country' => 'Eritrea'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 72],
            [
                'locale'  => 'et,et-EE',
                'country' => 'Estonia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 73],
            [
                'locale'  => 'en-ET,om-ET,ti-ET,so-ET,am-ET',
                'country' => 'Ethiopia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 74],
            [
                'locale'  => 'en-FK',
                'country' => 'Falkland Islands (Malvinas)'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 75],
            [
                'locale'  => 'da-FO',
                'country' => 'Faeroe Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 76],
            [
                'locale'  => 'en-FJ,fj',
                'country' => 'Fiji'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 77],
            [
                'locale'  => 'fi,fi-FI,sv-FI,se-FI,smn-FI,sms-FI,fi_FI',
                'country' => 'Finland'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 78],
            [
                'locale'  => 'fr,fr-FR,br-FR,co-FR,gsw-FR,oc-FR,fr_FR',
                'country' => 'France'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 79],
            [
                'locale'  => 'fr-GF',
                'country' => 'French Guiana'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 80],
            [
                'locale'  => 'fr-PF,ty',
                'country' => 'French Polynesia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 82],
            [
                'locale'  => 'fr-GA',
                'country' => 'Gabon'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 83],
            [
                'locale'  => 'en-GM',
                'country' => 'Gambia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 84],
            [
                'locale'  => 'ka,ka-GE',
                'country' => 'Georgia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 85],
            [
                'locale'  => 'de,de-DE,hsb-DE,dsb-DE,de_DE',
                'country' => 'Germany'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 86],
            [
                'locale'  => 'en-GH',
                'country' => 'Ghana'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 87],
            [
                'locale'  => 'en-GI',
                'country' => 'Gibraltar'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 88],
            [
                'locale'  => 'el-GR,el_GR,el',
                'country' => 'Greece'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 89],
            [
                'locale'  => 'da-GL,kl-GL',
                'country' => 'Greenland'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 90],
            [
                'locale'  => 'en-GD',
                'country' => 'Grenada'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 91],
            [
                'locale'  => 'fr-GP',
                'country' => 'Guadeloupe'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 92],
            [
                'locale'  => 'en-GU,ch-GU',
                'country' => 'Guam'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 93],
            [
                'locale'  => 'es-GT,qut-GT',
                'country' => 'Guatemala'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 94],
            [
                'locale'  => 'NULL',
                'country' => 'Guernsey'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 95],
            [
                'locale'  => 'fr-GN',
                'country' => 'Guinea'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 96],
            [
                'locale'  => 'pt-GW',
                'country' => 'Guinea-Bissau'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 97],
            [
                'locale'  => 'en-GY',
                'country' => 'Guyana'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 98],
            [
                'locale'  => 'ht,fr-HT',
                'country' => 'Haiti'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 100],
            [
                'locale'  => 'es-HN',
                'country' => 'Honduras'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 101],
            [
                'locale'  => 'zh-HK',
                'country' => 'China, Hong Kong Special Administrative Region'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 102],
            [
                'locale'  => 'hu-HU,hu',
                'country' => 'Hungary'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 103],
            [
                'locale'  => 'is,is-IS,is_IS',
                'country' => 'Iceland'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 104],
            [
                'locale'  => 'en-IN,hi,as-IN,bn-IN,gu-IN,hi-IN,kn-IN,kok-IN,ml-IN,mr-IN,or-IN,pa-IN,sa-IN,ta-IN,te-IN',
                'country' => 'India'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 105],
            [
                'locale'  => 'id,id-ID',
                'country' => 'Indonesia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 106],
            [
                'locale'  => 'fa-IR,fa_IR',
                'country' => 'Iran (Islamic Republic of)'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 107],
            [
                'locale'  => 'ar-IQ,cb_IQ',
                'country' => 'Iraq'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 108],
            [
                'locale'  => 'en-IE,ga-IE,ga_IE',
                'country' => 'Ireland'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 109],
            [
                'locale'  => 'NULL',
                'country' => 'Isle of Man'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 110],
            [
                'locale'  => 'he,ar-IL,en-IL,he-IL',
                'country' => 'Israel'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 111],
            [
                'locale'  => 'lt,it-IT,de-IT,fr-IT,lt_LT,it_IT,it',
                'country' => 'Italy'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 112],
            [
                'locale'  => 'en-JM,en-029',
                'country' => 'Jamaica'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 113],
            [
                'locale'  => 'ja, ja-JP,ja_JP',
                'country' => 'Japan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 114],
            [
                'locale'  => 'NULL',
                'country' => 'Jersey'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 115],
            [
                'locale'  => 'ar-JO',
                'country' => 'Jordan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 116],
            [
                'locale'  => 'kk,kk-KZ',
                'country' => 'Kazakhstan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 117],
            [
                'locale'  => 'en-KE,sw-KE',
                'country' => 'Kenya'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 118],
            [
                'locale'  => 'en-KI',
                'country' => 'Kiribati'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 119],
            [
                'locale'  => 'ar-KW',
                'country' => 'Kuwait'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 120],
            [
                'locale'  => 'ky,ky-KG',
                'country' => 'Kyrgyzstan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 121],
            [
                'locale'  => 'lo,lo-LA',
                'country' => 'Lao People\'s Democratic Republic'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 122],
            [
                'locale'  => 'lv,lv-LV',
                'country' => 'Latvia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 123],
            [
                'locale'  => 'ar-LB,fr-LB',
                'country' => 'Lebanon'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 124],
            [
                'locale'  => 'en-LS',
                'country' => 'Lesotho'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 125],
            [
                'locale'  => 'en-LR',
                'country' => 'Liberia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 126],
            [
                'locale'  => 'ar-LY',
                'country' => 'Libya'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 127],
            [
                'locale'  => 'de-LI',
                'country' => 'Liechtenstein'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 128],
            [
                'locale'  => 'lt,lt-LT',
                'country' => 'Lithuania'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 129],
            [
                'locale'  => 'lb,de-LU,fr-LU,lb-LU',
                'country' => 'Luxembourg'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 130],
            [
                'locale'  => 'zh-MO',
                'country' => 'China, Macao Special Administrative Region'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 131],
            [
                'locale'  => 'mk,mk-MK',
                'country' => 'The former Yugoslav Republic of Macedonia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 132],
            [
                'locale'  => 'fr-MG,mg',
                'country' => 'Madagascar'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 133],
            [
                'locale'  => 'NULL',
                'country' => 'Malawi'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 134],
            [
                'locale'  => 'ms-MY,ml,en-MY,ms_MY',
                'country' => 'Malaysia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 135],
            [
                'locale'  => 'dv,dv-MV',
                'country' => 'Maldives'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 136],
            [
                'locale'  => 'fr-ML',
                'country' => 'Mali'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 137],
            [
                'locale'  => 'mt,en-MT,mt-MT',
                'country' => 'Malta'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 138],
            [
                'locale'  => 'mh,en-MH',
                'country' => 'Marshall Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 139],
            [
                'locale'  => 'fr-MQ',
                'country' => 'Martinique'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 140],
            [
                'locale'  => 'ar-MR',
                'country' => 'Mauritania'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 141],
            [
                'locale'  => 'en-MU',
                'country' => 'Mauritius'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 142],
            [
                'locale'  => 'fr-YT',
                'country' => 'Mayotte'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 143],
            [
                'locale'  => 'es-MX,es_MX',
                'country' => 'Mexico'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 144],
            [
                'locale'  => 'en-FM',
                'country' => 'Micronesia (Federated States of)'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 145],
            [
                'locale'  => 'ro-mo',
                'country' => 'Republic of Moldova'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 146],
            [
                'locale'  => 'fr-MC',
                'country' => 'Monaco'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 147],
            [
                'locale'  => 'mn,mn-MN',
                'country' => 'Mongolia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 148],
            [
                'locale'  => 'sr-Cyrl-ME,sr-Latn-ME',
                'country' => 'Montenegro'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 149],
            [
                'locale'  => 'en-MS',
                'country' => 'Montserrat'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 150],
            [
                'locale'  => 'ar-MA',
                'country' => 'Morocco'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 151],
            [
                'locale'  => 'pt-MZ,vmw',
                'country' => 'Mozambique'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 152],
            [
                'locale'  => 'my',
                'country' => 'Myanmar'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 153],
            [
                'locale'  => 'en-NA',
                'country' => 'Namibia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 154],
            [
                'locale'  => 'na,en-NR',
                'country' => 'Nauru'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 155],
            [
                'locale'  => 'ne,ne-NP',
                'country' => 'Nepal'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 156],
            [
                'locale'  => 'nl-NL,fy-NL,nl_NL,nl',
                'country' => 'Netherlands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 157],
            [
                'locale'  => 'fr-NC',
                'country' => 'New Caledonia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 158],
            [
                'locale'  => 'en-NZ,mi-NZ,',
                'country' => 'New Zealand'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 159],
            [
                'locale'  => 'es-NI',
                'country' => 'Nicaragua'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 160],
            [
                'locale'  => 'fr-NE',
                'country' => 'Niger'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 161],
            [
                'locale'  => 'en-NG,ha-Latn-NG,ig-NG,yo-NG',
                'country' => 'Nigeria'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 162],
            [
                'locale'  => 'niu,en-NU',
                'country' => 'Niue'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 163],
            [
                'locale'  => 'en-NF',
                'country' => 'Norfolk Island'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 164],
            [
                'locale'  => 'ko-KP',
                'country' => 'Democratic People\'s Republic of Korea'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 165],
            [
                'locale'  => 'ch-MP,en-MP',
                'country' => 'Northern Mariana Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 166],
            [
                'locale'  => 'nb,no,nb-NO,nn-NO,se-NO,sma-NO,smj-NO,nn,nn_NO,no_NO_NY,no_NO,nb_NO,nb_SJ,nb-no,n',
                'country' => 'Norway'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 167],
            [
                'locale'  => 'ar-OM',
                'country' => 'Oman'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 168],
            [
                'locale'  => 'ur-PK,en-PK',
                'country' => 'Pakistan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 169],
            [
                'locale'  => 'en-PW',
                'country' => 'Palau'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 170],
            [
                'locale'  => 'ar-PS',
                'country' => 'State of Palestine'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 171],
            [
                'locale'  => 'es-PA',
                'country' => 'Panama'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 172],
            [
                'locale'  => 'en-PG',
                'country' => 'Papua New Guinea'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 173],
            [
                'locale'  => 'es-PY,gn',
                'country' => 'Paraguay'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 174],
            [
                'locale'  => 'es-PE,quz-PE',
                'country' => 'Peru'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 175],
            [
                'locale'  => 'tl,en-PH,fil,fil-PH',
                'country' => 'Philippines'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 176],
            [
                'locale'  => 'en-PN',
                'country' => 'Pitcairn'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 177],
            [
                'locale'  => 'pl,pl-PL,pl_PL',
                'country' => 'Poland'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 178],
            [
                'locale'  => 'pt-PT,pt_PT,pt',
                'country' => 'Portugal'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 179],
            [
                'locale'  => 'en-PR,es-PR',
                'country' => 'Puerto Rico'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 180],
            [
                'locale'  => 'ar-QA',
                'country' => 'Qatar'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 181],
            [
                'locale'  => 'ro,ro-RO',
                'country' => 'Romania'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 182],
            [
                'locale'  => 'ru,ba-RU,ru-RU,sah-RU,tt-RU,ru_RU',
                'country' => 'Russian Federation'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 183],
            [
                'locale'  => 'rw,en-RW,fr-RW,rw-RW',
                'country' => 'Rwanda'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 184],
            [
                'locale'  => 'fr-RE',
                'country' => 'RÃ©union'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 185],
            [
                'locale'  => 'en-AS',
                'country' => 'Samoa'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 186],
            [
                'locale'  => 'it-SM',
                'country' => 'San Marino'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 187],
            [
                'locale'  => 'ar-SA',
                'country' => 'Saudi Arabia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 188],
            [
                'locale'  => 'fr-SN,wo-SN',
                'country' => 'Senegal'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 189],
            [
                'locale'  => 'sr,sr-Cyrl-BA,sr-BA,sr-Latn-BA,sr-Latn-RS,sr-Cyrl-RS,sr_CS,sr_RS',
                'country' => 'Serbia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 190],
            [
                'locale'  => 'en-SC,fr-SC',
                'country' => 'Seychelles'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 191],
            [
                'locale'  => 'en-SL',
                'country' => 'Sierra Leone'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 192],
            [
                'locale'  => 'en-SG,ms-SG,ta-SG,zh-SG',
                'country' => 'Singapore'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 193],
            [
                'locale'  => 'NULL',
                'country' => 'Sint Maarten (Dutch part)'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 194],
            [
                'locale'  => 'sk,sk-SK',
                'country' => 'Slovakia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 195],
            [
                'locale'  => 'sl,sl-SI',
                'country' => 'Slovenia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 196],
            [
                'locale'  => 'en-SB',
                'country' => 'Solomon Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 197],
            [
                'locale'  => 'so-SO,ar-SO,it-SO,en-SO',
                'country' => 'Somalia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 198],
            [
                'locale'  => 'en-ZA,af,nso-ZA,af-ZA,tn-ZA,xh-ZA,zu-ZA',
                'country' => 'South Africa'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 200],
            [
                'locale'  => 'ko-KR',
                'country' => 'Republic of Korea'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 201],
            [
                'locale'  => 'NULL',
                'country' => 'South Sudan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 202],
            [
                'locale'  => 'es,es-ES,ca,gl,eu,ca-ES,gl-ES,eu-ES,es_ES,ca_ES,ca-ES',
                'country' => 'Spain'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 203],
            [
                'locale'  => 'si,si-LK',
                'country' => 'Sri Lanka'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 204],
            [
                'locale'  => 'NULL',
                'country' => 'Saint BarthÃ©lemy'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 205],
            [
                'locale'  => 'en-SH',
                'country' => 'Saint Helena'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 206],
            [
                'locale'  => 'en-KN',
                'country' => 'Saint Kitts and Nevis'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 207],
            [
                'locale'  => 'en-LC',
                'country' => 'Saint Lucia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 208],
            [
                'locale'  => 'NULL',
                'country' => 'Saint Martin (French part)'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 209],
            [
                'locale'  => 'fr-PM',
                'country' => 'Saint Pierre and Miquelon'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 210],
            [
                'locale'  => 'en-VC',
                'country' => 'Saint Vincent and the Grenadines'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 211],
            [
                'locale'  => 'ar-SD',
                'country' => 'Sudan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 212],
            [
                'locale'  => 'nl-SR',
                'country' => 'Suriname'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 213],
            [
                'locale'  => 'NULL',
                'country' => 'Svalbard and Jan Mayen Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 214],
            [
                'locale'  => 'en-SZ,ss-SZ',
                'country' => 'Swaziland'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 215],
            [
                'locale'  => 'sv,sv-SE,fi-SE,se-SE,sma-SE,smj-SE,en_SE',
                'country' => 'Sweden'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 216],
            [
                'locale'  => 'de-CH,fr-CH,it-CH,rm-CH,gsw_CH,gsw_AT,de_CH',
                'country' => 'Switzerland'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 217],
            [
                'locale'  => 'ar-SY,syr-SY',
                'country' => 'Syrian Arab Republic'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 218],
            [
                'locale'  => 'pt-ST',
                'country' => 'Sao Tome and Principe'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 220],
            [
                'locale'  => 'tg,tg-Cyrl-TJ,tg-TJ',
                'country' => 'Tajikistan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 221],
            [
                'locale'  => 'sw-TZ',
                'country' => 'United Republic of Tanzania'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 222],
            [
                'locale'  => 'th,th-TH',
                'country' => 'Thailand'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 223],
            [
                'locale'  => 'tet,pt-TL,',
                'country' => 'Timor-Leste'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 224],
            [
                'locale'  => 'fr-TG',
                'country' => 'Togo'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 225],
            [
                'locale'  => 'tkl,en-TK',
                'country' => 'Tokelau'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 226],
            [
                'locale'  => 'to,en-TO',
                'country' => 'Tonga'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 227],
            [
                'locale'  => 'en-TT',
                'country' => 'Trinidad and Tobago'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 228],
            [
                'locale'  => 'ar-TN',
                'country' => 'Tunisia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 229],
            [
                'locale'  => 'tr-TR',
                'country' => 'Turkey'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 230],
            [
                'locale'  => 'tk,tk-TM',
                'country' => 'Turkmenistan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 231],
            [
                'locale'  => 'en-TC',
                'country' => 'Turks and Caicos Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 232],
            [
                'locale'  => 'tvl',
                'country' => 'Tuvalu'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 234],
            [
                'locale'  => 'en-VI',
                'country' => 'United States Virgin Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 235],
            [
                'locale'  => 'en,en-GB,cy-GB,gd-GB,en_PI,en_GB',
                'country' => 'United Kingdom'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 236],
            [
                'locale'  => 'en-US,es-US,en_US,es_US',
                'country' => 'United States of America'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 237],
            [
                'locale'  => 'en-UG',
                'country' => 'Uganda'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 238],
            [
                'locale'  => 'uk,ru-UA,uk-UA',
                'country' => 'Ukraine'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 239],
            [
                'locale'  => 'ar-AE',
                'country' => 'United Arab Emirates'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 240],
            [
                'locale'  => 'es-UY',
                'country' => 'Uruguay'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 241],
            [
                'locale'  => 'uz,uz-Cyrl-UZ,uz-Latn-UZ',
                'country' => 'Uzbekistan'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 242],
            [
                'locale'  => 'en-VU,fr-VU',
                'country' => 'Vanuatu'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 243],
            [
                'locale'  => 'NULL',
                'country' => 'Holy See'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 244],
            [
                'locale'  => 'es-VE',
                'country' => 'Venezuela (Bolivarian Republic of)'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 245],
            [
                'locale'  => 'vi,vi-VN',
                'country' => 'Viet Nam'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 246],
            [
                'locale'  => 'wls,fud,fr-WF',
                'country' => 'Wallis and Futuna Islands'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 247],
            [
                'locale'  => 'NULL',
                'country' => 'Western Sahara'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 248],
            [
                'locale'  => 'ar-YE',
                'country' => 'Yemen'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 249],
            [
                'locale'  => 'en-ZM',
                'country' => 'Zambia'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 250],
            [
                'locale'  => 'en-ZW',
                'country' => 'Zimbabwe'
            ]
        );

        CountryLang::updateOrCreate(
            ['id' => 251],
            [
                'locale'  => 'es-xl,es_XL,es_LA,ES-LA',
                'country' => 'Latin America'
            ]
        );

    }
}
