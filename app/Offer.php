<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'hotel_oferta';
    public $timestamps = false;
    protected $visible = ['id','start','end','image_url','booking_engine_code', 'n_triggers', 'offerLang','offerType','category'];
    protected $appends = ['start','end','image_url'];

    public function getStartAttribute()
    {
        return $this->attributes['inicio'] ;
    }

    public function getEndAttribute()
    {
        return $this->attributes['fin'] ;
    }

    public function getImageUrlAttribute()
    {
        return $this->attributes['img'] ;
    }

    public function offerLang()
    {
        return $this->hasMany('App\OfferLang', 'id_oferta');
    }

    public function offerGoal()
    {
        return $this->hasMany('App\OfferGoal', 'id', 'offer_id');
    }

    public function offerType()
    {
        return $this->hasOne('App\OfferType', 'id', 'id_tipo_oferta');
    }

    public function category()
    {
        return $this->hasOne('App\CategoryOffer', 'id', 'id_categoria');
    }
}
