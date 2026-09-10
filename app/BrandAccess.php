<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandAccess extends Model
{
    public $timestamps = true;
    protected $table = 'brand_access';
    protected $fillable = ['brand_id', 'codes'];


    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
}
