<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferLang extends Model
{
    protected $table = 'hotel_oferta_lang';
    public $timestamps = false;
    protected $hidden = ['lang_ok','id_oferta','id','nombre','descripcion','condiciones'];
    protected $appends = ['name','description','conditions'];

    public function getNameAttribute()
    {
        return $this->attributes['nombre'] ;
    }

    public function getDescriptionAttribute()
    {
        return $this->attributes['descripcion'] ;
    }

    public function getConditionsAttribute()
    {
        return $this->attributes['condiciones'] ;
    }
    public function oferta()
    {
        return $this->belongsTo('App\Offer', 'id_oferta');
    }
}
