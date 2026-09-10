<?php

use Illuminate\Database\Seeder;
use App\BrandCustomText;

class GenesisBrandCustomTextsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        BrandCustomText::updateOrCreate(
            ['id' => 1],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 1,
                'value'          => '<a class="privacy"> Privacy Policy</a>',
                'custom_text_id' => 1
            ]
        );

        BrandCustomText::updateOrCreate(
            ['id' => 2],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 2,
                'value'          => '<a class="privacy"> Politica de privacidad</a>',
                'custom_text_id' => 1
            ]
        );

        BrandCustomText::updateOrCreate(
            ['id' => 3],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 3,
                'value'          => '<a class="privacy"> Politique de confidentialité</a>',
                'custom_text_id' => 1
            ]
        );

        BrandCustomText::updateOrCreate(
            ['id' => 4],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 4,
                'value'          => '<a class="privacy"> Datenschutzerklärung</a>',
                'custom_text_id' => 1
            ]
        );

        BrandCustomText::updateOrCreate(
            ['id' => 5],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 5,
                'value'          => '<a class="privacy"> Política de Privacitat</a>',
                'custom_text_id' => 1
            ]
        );

        BrandCustomText::updateOrCreate(
            ['id' => 6],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 6,
                'value'          => '<a class="privacy"> Legge sulla privacy</a>',
                'custom_text_id' => 1
            ]
        );

        BrandCustomText::updateOrCreate(
            ['id' => 7],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 7,
                'value'          => '<a class="privacy">隐私政策</a>',
                'custom_text_id' => 1
            ]
        );

        BrandCustomText::updateOrCreate(
            ['id' => 8],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 8,
                'value'          => '<a class="privacy">Política de Privacidade</a>',
                'custom_text_id' => 1
            ]
        );

        BrandCustomText::updateOrCreate(
            ['id' => 9],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 9,
                'value'          => '<a class="privacy">правила за поверителност</a>',
                'custom_text_id' => 1
            ]
        );

        BrandCustomText::updateOrCreate(
            ['id' => 10],
            [
                'hotel_id'       => null,
                'chain_id'       => null,
                'language_id'    => 10,
                'value'          => '<a class="privacy">Политикой конфиденциальности</a>',
                'custom_text_id' => 1
            ]
        );

    }
}
