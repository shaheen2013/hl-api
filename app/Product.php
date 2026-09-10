<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->attributes['producto'];
    }
    public function brands()
    {
        return $this->belongsToMany('App\Brand', 'brand_product');
    }
}
