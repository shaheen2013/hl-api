<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryOffer extends Model
{
    protected $table = 'categoria_oferta';
    public $timestamps = false;
    protected $visible = ['name'];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->attributes['categoria_en'] ;
    }
}
