<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class HotelProduct extends Model
{
    use InsertOnDuplicateKey;

    protected $guarded = [];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Product', 'products_id');
    }

    public function hotel()
    {
        return $this->hasMany('App\Hotel', 'hotels_id');
    }
}
