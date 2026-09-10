<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelRoom extends Model
{
    public $timestamps = false;
    protected $table = 'hotel_rooms';
    protected $guarded = ['id'];

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'id_hotel');
    }
}
