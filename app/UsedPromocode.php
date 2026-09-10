<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsedPromocode extends Model
{
    protected $table = 'used_promocode';
    public $timestamps = false;

    public function userCupones()
    {
        return $this->hasOne('App\UserCupones', 'id', 'id_cupon');
    }

    public function shareTypes()
    {
        return $this->belongsTo('App\ShareTypes', 'id_tipo_share');
    }

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'id_hotel');
    }
}
