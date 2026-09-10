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

class PermisosCadenas extends Model
{
    protected $table = 'permisos_cadenas';
    public $timestamps = false;

    public function cadenaGuid()
    {
        return $this->belongsTo('App\Cadena', 'id_cadena');
    }
}
