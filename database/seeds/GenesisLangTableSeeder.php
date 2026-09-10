<?php

use Illuminate\Database\Seeder;
use App\Lang;

class GenesisLangTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Lang::updateOrCreate(
            ['id' => 1],
            [
                'lang'    => 'bg',
                'img'     => 'bg.png',
                'country' => 'Bulgarian',
                'system'  => 0,
                'content' => 1
            ]
        );

        Lang::updateOrCreate(
            ['id' => 2],
            [
                'lang'    => 'ca',
                'img'     => 'ca.png',
                'country' => 'Catalan',
                'system'  => 0,
                'content' => 1
            ]
        );

        Lang::updateOrCreate(
            ['id' => 3],
            [
                'lang'    => 'cs',
                'img'     => 'cs.png',
                'country' => 'Czech',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 4],
            [
                'lang'    => 'da',
                'img'     => 'da.png',
                'country' => 'Danish',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 5],
            [
                'lang'    => 'de',
                'img'     => 'de.png',
                'country' => 'German',
                'system'  => 0,
                'content' => 1
            ]
        );

        Lang::updateOrCreate(
            ['id' => 6],
            [
                'lang'    => 'pt_BR',
                'img'     => 'pt-BR.png',
                'country' => 'Brazilian Portuguese',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 7],
            [
                'lang'    => 'el',
                'img'     => 'el.png',
                'country' => 'Greek',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 8],
            [
                'lang'    => 'en',
                'img'     => 'en.png',
                'country' => 'English',
                'system'  => 1,
                'content' => 1
            ]
        );

        Lang::updateOrCreate(
            ['id' => 9],
            [
                'lang'    => 'es',
                'img'     => 'es.png',
                'country' => 'Spanish',
                'system'  => 1,
                'content' => 1
            ]
        );

        Lang::updateOrCreate(
            ['id' => 10],
            [
                'lang'    => 'fa',
                'img'     => 'fa.png',
                'country' => 'Persian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 11],
            [
                'lang'    => 'fr',
                'img'     => 'fr.png',
                'country' => 'French',
                'system'  => 0,
                'content' => 1
            ]
        );

        Lang::updateOrCreate(
            ['id' => 12],
            [
                'lang'    => 'gl',
                'img'     => 'gl.png',
                'country' => 'Galician',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 13],
            [
                'lang'    => 'hi',
                'img'     => 'hi.png',
                'country' => 'Hindi',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 14],
            [
                'lang'    => 'hr',
                'img'     => 'hr.png',
                'country' => 'Croatian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 15],
            [
                'lang'    => 'hu',
                'img'     => 'hu.png',
                'country' => 'Hungarian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 16],
            [
                'lang'    => 'id',
                'img'     => 'id.png',
                'country' => 'Indonesian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 17],
            [
                'lang'    => 'it',
                'img'     => 'it.png',
                'country' => 'Italian',
                'system'  => 0,
                'content' => 1
            ]
        );

        Lang::updateOrCreate(
            ['id' => 18],
            [
                'lang'    => 'km',
                'img'     => 'km.png',
                'country' => 'Cambodian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 19],
            [
                'lang'    => 'ko',
                'img'     => 'ko.png',
                'country' => 'Korean',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 20],
            [
                'lang'    => 'zh_TW',
                'img'     => 'zh-TW.png',
                'country' => 'Traditional Chinese',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 21],
            [
                'lang'    => 'lv',
                'img'     => 'lv.png',
                'country' => 'Latvian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 22],
            [
                'lang'    => 'nl',
                'img'     => 'nl.png',
                'country' => 'Dutch',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 23],
            [
                'lang'    => 'no',
                'img'     => 'no.png',
                'country' => 'Norwegian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 24],
            [
                'lang'    => 'pl',
                'img'     => 'pl.png',
                'country' => 'Polish',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 25],
            [
                'lang'    => 'pt',
                'img'     => 'pt.png',
                'country' => 'Portuguese',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 26],
            [
                'lang'    => 'ro',
                'img'     => 'ro.png',
                'country' => 'Romanian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 27],
            [
                'lang'    => 'ru',
                'img'     => 'ru.png',
                'country' => 'Russian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 28],
            [
                'lang'    => 'sk',
                'img'     => 'sk.png',
                'country' => 'Slovak',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 29],
            [
                'lang'    => 'sl',
                'img'     => 'sl.png',
                'country' => 'Slovenian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 30],
            [
                'lang'    => 'sv',
                'img'     => 'sv.png',
                'country' => 'Swedish',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 31],
            [
                'lang'    => 'tr',
                'img'     => 'tr.png',
                'country' => 'Turkish',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 32],
            [
                'lang'    => 'uk',
                'img'     => 'uk.png',
                'country' => 'Ukrainian',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 33],
            [
                'lang'    => 'vi',
                'img'     => 'vi.png',
                'country' => 'Vietnamese',
                'system'  => 0,
                'content' => 0
            ]
        );

        Lang::updateOrCreate(
            ['id' => 34],
            [
                'lang'    => 'zh',
                'img'     => 'zh.png',
                'country' => 'Simplified Chinese',
                'system'  => 0,
                'content' => 1
            ]
        );
    }
}
