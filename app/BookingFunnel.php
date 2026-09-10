<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingFunnel extends Model
{
    protected $table = 'new_booking_funnel';
    public $timestamps = false;
    protected $fillable = ['user_id', 'referrer_id', 'brand_id', 'session', 'date', 'booking_action', 'source', 'source_action'];
}
