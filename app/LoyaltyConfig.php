<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoyaltyConfig extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
}
