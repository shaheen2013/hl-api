<?php

use Illuminate\Database\Seeder;
use App\OfferType;

class GenesisTiposOfertaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        OfferType::updateOrCreate(
            ['id' => 1],
            [
                'id_tipo_oferta'  => 'des',
                'tipo_adq_ret_es' => 'Descuento',
                'tipo_adq_ret_en' => 'Discount',
                'adq'             => 1,
                'ret'             => 1,
                'ref'             => 1
            ]
        );

        OfferType::updateOrCreate(
            ['id' => 2],
            [
                'id_tipo_oferta'  => 'ngr',
                'tipo_adq_ret_es' => 'Noche gratis',
                'tipo_adq_ret_en' => 'Free night',
                'adq'             => 1,
                'ret'             => 1,
                'ref'             => 1
            ]
        );

        OfferType::updateOrCreate(
            ['id' => 3],
            [
                'id_tipo_oferta'  => 'upg',
                'tipo_adq_ret_es' => 'Upgrade',
                'tipo_adq_ret_en' => 'Upgrade',
                'adq'             => 1,
                'ret'             => 1,
                'ref'             => 1
            ]
        );

        OfferType::updateOrCreate(
            ['id' => 4],
            [
                'id_tipo_oferta'  => 'chk',
                'tipo_adq_ret_es' => 'Check-in',
                'tipo_adq_ret_en' => 'Check-in',
                'adq'             => 0,
                'ret'             => 1,
                'ref'             => 1
            ]
        );
    }
}
