<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferGoal extends Model
{
    public $timestamps = false;
    protected $fillable = ['brand_id'];

    public function offer()
    {
        return $this->hasOne('App\Offer', 'id', 'offer_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
}
