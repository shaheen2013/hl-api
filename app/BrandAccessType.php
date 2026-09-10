<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandAccessType extends Model
{
    public $timestamps = true;
    protected $table = 'brand_access_type';
    protected $fillable = ['brand_id', 'access_type_id', 'active'];


    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function accessType()
    {
        return $this->belongsTo('App\AccessType');
    }
}
