<?php

use Illuminate\Database\Seeder;
use App\CategoryOffer;

class GenesisCategoriaOfertaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        CategoryOffer::updateOrCreate(
            ['id' => 1],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'gas',
                'categoria_es'        => 'Gastronom',
                'categoria_en'        => 'Food'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 2],
            [
                'id_tipo_oferta'      => 'des',
                'id_categoria_oferta' => 'des',
                'categoria_es'        => 'Descuento',
                'categoria_en'        => 'Stay discount'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 3],
            [
                'id_tipo_oferta'      => 'ngr',
                'id_categoria_oferta' => 'ngr',
                'categoria_es'        => 'Noche gratis',
                'categoria_en'        => 'Free night'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 4],
            [
                'id_tipo_oferta'      => 'upg',
                'id_categoria_oferta' => 'upg',
                'categoria_es'        => 'Upgrade',
                'categoria_en'        => 'Upgrade'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 5],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'beb',
                'categoria_es'        => 'Bebidas',
                'categoria_en'        => 'Beverages'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 6],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'dep',
                'categoria_es'        => 'Deportes',
                'categoria_en'        => 'Sports'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 7],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'eve',
                'categoria_es'        => 'Eventos',
                'categoria_en'        => 'Events'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 8],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'rel',
                'categoria_es'        => 'Relax',
                'categoria_en'        => 'Relax'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 9],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'oci',
                'categoria_es'        => 'Ocio',
                'categoria_en'        => 'Leisure'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 10],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'tra',
                'categoria_es'        => 'Transporte',
                'categoria_en'        => 'Transportation'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 11],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'int',
                'categoria_es'        => 'Internet',
                'categoria_en'        => 'Internet'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 12],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'exc',
                'categoria_es'        => 'Excursiones',
                'categoria_en'        => 'Activities'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 13],
            [
                'id_tipo_oferta'      => 'so',
                'id_categoria_oferta' => '0',
                'categoria_es'        => 'Selecciona una opci&oacute;n',
                'categoria_en'        => 'Select one'
            ]
        );

        CategoryOffer::updateOrCreate(
            ['id' => 14],
            [
                'id_tipo_oferta'      => 'chk',
                'id_categoria_oferta' => 'cre',
                'categoria_es'        => 'Cr&eacute;dito',
                'categoria_en'        => 'Credit'
            ]
        );
    }
}
