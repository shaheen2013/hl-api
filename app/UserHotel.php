<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHotel extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'id_usuario');
    }

    public function brand()
    {
        return $this->hasOne('App\Brand', 'hotel_id', 'id_hotel');
    }
}
