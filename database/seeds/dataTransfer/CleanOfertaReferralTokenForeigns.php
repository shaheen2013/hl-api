<?php

use Illuminate\Database\Seeder;
use App\OfertaReferralToken;

class CleanOfertaReferralTokenForeigns extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OfertaReferralToken::doesntHave('userCupones')->update(['id_cupon' => null]);
        OfertaReferralToken::doesntHave('shareTypes')->update(['id_tipo_share' => null]);
        OfertaReferralToken::doesntHave('hotel')->update(['id_hotel' => null]);
    }
}
