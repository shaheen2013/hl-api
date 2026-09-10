<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class HotelSatisfaction extends Model
{
    use InsertOnDuplicateKey;

    public $timestamps = false;
    protected $table = 'hotel_satisfaction';
    protected $guarded = ['id'];
    protected $appends = ['send_days', 'punt_min', 'reviewAverageScore', 'ignore_rating', 'send_thanks_mail', 'send_to_non_customers'];

    public function getSendDaysAttribute()
    {
        return $this->attributes['diasEnvio'];
    }

    public function getPuntMinAttribute()
    {
        return $this->attributes['puntMin'];
    }

    public function getReviewAverageScoreAttribute()
    {
        return $this->attributes['review_average_score'];
    }

    public function getIgnoreRatingAttribute()
    {
        return $this->attributes['ignoreRating'];
    }

    public function getSendThanksMailAttribute()
    {
        return $this->attributes['sendThanksMail'];
    }

    public function getSendToNonCustomersAttribute()
    {
        return $this->attributes['sendToNonCustomers'];
    }

    public function hotel()
    {
        return $this->belongsTo('App\Hotel');
    }
}
