<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandProductConfig extends Model
{
    public $timestamps = true;
    protected $table = 'brand_product_config';
    protected $guarded = ['id'];


    public function productConfig()
    {
        return $this->belongsTo('App\ProductConfig');
    }

    public function brandProductConfig()
    {
        return $this->belongsTo('App\BrandProduct');
    }
}
