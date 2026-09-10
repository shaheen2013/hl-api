<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelStaff extends Model
{
    protected $table = 'hotel_staff';
    public $timestamps = false;

    public function hotel()
    {
        return $this->hasMany('App\HotelStaffHotels');
    }

    public function role()
    {
        return $this->belongsTo('App\HotelStaffRole', 'id_role');
    }
}
