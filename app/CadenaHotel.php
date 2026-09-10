<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/02/2018
 * Time: 16:57
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CadenaHotel extends Model
{
    protected $table = 'cadena_hotel';
    public $timestamps = false;

    public function cadena()
    {
        return $this->belongsTo('App\Cadena', 'id', 'id_cadena');
    }

    public function hotel()
    {
        return $this->hasOne('App\Hotel', 'id', 'id_hotel');
    }
}
