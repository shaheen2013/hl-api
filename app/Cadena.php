<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/02/2018
 * Time: 16:54
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cadena extends Model
{
    protected $table = 'cadena';
    public $timestamps = false;
    protected $visible = [
        'id',
        'nombre',
        'logo',
        'email',
        'email_envio',
        'brandProduct',
        'cadenaHotel'
    ];

    protected $fillable = [
        'nombre',
        'descripcion',
        'email_contacto',
        'telefono_contacto',
        'stay_time',
        'loyalty_min_visits',
    ];
    

    public function cadenaGuid()
    {
        return $this->hasOne('App\CadenaGuid', 'id_cadena');
    }

    public function cadenaHotel()
    {
        return $this->hasMany('App\CadenaHotel', 'id_cadena');
    }

    public function brand()
    {
        return $this->hasOne('App\Brand', 'chain_id');
    }

    public function brandProduct()
    {
        return $this->hasManyThrough('App\BrandProduct', 'App\Brand', 'chain_id');
    }

    public function brandChilds()
    {
        return $this->hasMany('App\Brand', 'parent_id');
    }
}
