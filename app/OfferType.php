<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferType extends Model
{
    protected $table = 'tipos_oferta';
    public $timestamps = false;
    protected $visible = ['name'];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->attributes['tipo_adq_ret_en'] ;
    }
}
