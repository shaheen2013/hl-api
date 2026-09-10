<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermisosHoteles extends Model
{
    protected $table = 'permisos_hoteles';
    public $timestamps = false;
    protected $hidden = [];
    protected $guarded = [];

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'id_hotel');
    }
}
