<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandProtocol extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'brand_id',
        'service',
        'treatment'
    ];

    public static function getTableName()
    {
        return with(new static())->getTable();
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
}
