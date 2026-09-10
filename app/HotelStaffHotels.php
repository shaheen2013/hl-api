<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelStaffHotels extends Model
{
    protected $table = 'hotel_staff_hotels';
    public $timestamps = false;

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'id', 'hotel_id');
    }

    public function staff()
    {
        return $this->hasOne('App\HotelStaff', 'id', 'hotel_staff_id');
    }
}
