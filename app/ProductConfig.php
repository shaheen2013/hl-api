<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductConfig extends Model
{
    public $timestamps = false;
    protected $table = 'product_config';

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function brandProductConfig()
    {
        return $this->hasMany('App\BrandProductConfig');
    }
}
