<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelReview extends Model
{
    public $timestamps = false;
    protected $table = 'hotel_review';
    protected $guarded = ['id'];
    protected $appends = ['send_days', 'ignore_rating'];
    protected $visible = ['id_hotel', 'send_days','ignore_rating'];

    public function getSendDaysAttribute()
    {
        return $this->attributes['diasEnvio'];
    }

    public function getIgnoreRatingAttribute()
    {
        return $this->attributes['ignoreRating'];
    }

    public function hotel()
    {
        return $this->belongsTo('App\Hotel');
    }
}
