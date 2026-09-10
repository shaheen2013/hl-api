<?php

use Illuminate\Database\Seeder;
use App\UsedPromocode;

class CleanUsedPromocodeForeigns extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsedPromocode::doesntHave('userCupones')->update(['id_cupon' => null]);
        UsedPromocode::doesntHave('shareTypes')->update(['id_tipo_share' => null]);
        UsedPromocode::doesntHave('hotel')->update(['id_hotel' => null]);
    }
}
