<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Brand extends Model
{
    protected $table = 'brands';

    public $timestamps = false;
    protected $hidden = [];
    protected $fillable = [
        'background_color'
    ];

    public $appends = ['brandInfo', 'brandTypeInfo'];

    public function getBrandInfoAttribute()
    {
        $tagName = ["brandInfo_" . $this->attributes['id']];
        $cacheName = "brandInfo_" . $this->attributes['id'];
        $brandInfo  = Cache::tags($tagName)->get($cacheName);

        if (!$brandInfo) {
            if ($this->attributes['hotel_id']) {
                $brandInfo = Hotel::find($this->attributes['hotel_id'])->load('brandProduct', 'bookingEngines', 'hotelGuid', 'timeZone');
            } else {
                $brandInfo = Cadena::find($this->attributes['chain_id'])->load('cadenaHotel', 'cadenaHotel.hotel', 'cadenaHotel.hotel.brandProduct', 'cadenaHotel.hotel.hotelGuid', 'cadenaHotel.hotel.brand', 'cadenaHotel.hotel.bookingEngines', 'brandProduct');
            }

            Cache::tags($tagName)->put($cacheName, $brandInfo, now()->addDays(10));
        }

        return $brandInfo;
    }

    public function getBrandTypeInfoAttribute()
    {
        $brandType = new \stdClass();
        if ($this->attributes['hotel_id']) {
            $brandType->id = $this->attributes['hotel_id'];
            $brandType->field = 'hotel_id';
        } else {
            $brandType->id = $this->attributes['chain_id'];
            $brandType->field = 'chain_id';
        }

        return $brandType;
    }

    public function isIndependentHotel()
    {
        return $this->attributes['hotel_id'] && !$this->attributes['parent_id'];
    }

    public function getBrandInfo()
    {
        if ($this->attributes['chain_id']) {
            $chain = Cadena::find($this->attributes['chain_id']);
            $info = [
                'id'       => $this->attributes['id'],
                'chain_id' => $this->attributes['chain_id'],
                'name'     => $chain->nombre,
                'logo'     => $chain->logo
            ];

            return $info;
        }

        $hotel = Hotel::find($this->attributes['hotel_id']);
        $info = [
            'id'       => $this->attributes['id'],
            'hotel_id' => $this->attributes['hotel_id'],
            'name'     => $hotel->hotelName,
            'logo'     => $hotel->logo
        ];

        return $info;
    }

    public function chain()
    {
        return $this->belongsTo('App\Cadena', 'chain_id');
    }

    public function brandType()
    {
        return $this->belongsTo('App\BrandType', 'brand_type_id');
    }

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'hotel_id');
    }

    public function hotels()
    {
        return $this->belongsToMany('App\Hotel', 'cadena_hotel', 'id_cadena', 'id_hotel');
    }

    public function brandProtocol()
    {
        return $this->hasMany('App\BrandProtocol');
    }

    public function category()
    {
        return $this->hasMany('App\Category');
    }

    public function questionBrand()
    {
        return $this->hasMany('App\QuestionBrand');
    }

    public function children()
    {
        return $this->hasMany('App\Brand', 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parent()
    {
        return $this->belongsTo('App\Brand', 'parent_id');
    }

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }

    public function loyaltyConfig()
    {
        return $this->hasOne('App\LoyaltyConfig');
    }

    public function offerWifi()
    {
        return $this->hasMany('App\BrandOfferWifi');
    }

    public function users()
    {
        return $this->hasMany('App\UserBrand');
    }
}
