<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandOfferWifi extends Model
{
    protected $table = 'brand_offer_wifi';
    public $appends = ['img', 'description'];
    protected $fillable = [
        'brand_id',
        'offer_id',
        'condition',
        'offer_type',
        'period',
        'valid_from',
        'valid_to',
        'is_default',
        'accommodated',
        'non_accommodated',
    ];
    protected $visible = [
        'id',
        'offer_id',
        'condition',
        'offer_type',
        'period',
        'valid_from',
        'valid_to',
        'is_default',
        'img',
        'description',
        'accommodated',
        'non_accommodated',
    ];

    public function getImgAttribute()
    {
        return data_get($this, 'offer.img');
    }

    public function getDescriptionAttribute()
    {
        return $this->offer->offerLang->keyBy('lang');
    }

    public function offer()
    {
        return $this->belongsTo('App\Offer');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
}
