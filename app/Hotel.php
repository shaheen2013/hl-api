<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 19/01/2018
 * Time: 13:52
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\FullTextSearch;

class Hotel extends Model
{
    use FullTextSearch;

    protected $table = 'hoteles';
    public $timestamps = false;
    public $appends = ['name', 'brandId'];
    protected $visible = [
        'id',
        'brandId',
        'email',
        'name',
        'city',
        'street',
        'place_name',
        'place_country',
        'place_adm_area',
        'estrellas',
        'sending_email',
        'brandProduct',
        'fotoBg',
        'logo',
        'hotelGuid',
        'bookingEngines',
        'stay_time',
        'timeZone',
        'activated'
    ];

    protected $fillable = [
        'email',
        'name',
        'city',
        'street',
        'place_name',
        'place_country',
        'place_adm_area',
        'estrellas',
        'sending_email',
        'brandProduct',
        'fotoBg',
        'logo',
        'hotelName',
        'hotelGuid',
        'bookingEngines',
        'stay_time',
        'time_zone_id',
        'activated',
        'n_habitaciones'
    ];

    protected $searchable = [
        'hotelName',
        'place_name',
        'country'
    ];

    public function getNameAttribute()
    {
        return $this->attributes['hotelName'];
    }

    public function getWebsiteReservaAttribute()
    {
        return $this->attributes['website'];
    }

    public function getBackgroundPhotoAttribute()
    {
        return $this->attributes['fotoBg'];
    }

    public function hotelGuid()
    {
        return $this->hasOne('App\HotelGuid', 'id_hotel');
    }

    public function getBrandIdAttribute()
    {
        $brand = Brand::where('hotel_id', $this->attributes['id'])->first();
        return $brand->id;
    }

    public function brand()
    {
        return $this->hasOne('App\Brand', 'hotel_id');
    }

    public function bookingEngines()
    {
        return $this->belongsTo('App\BookingEngine', 'booking_engine');
    }

    public function hotelWifiIntegrations()
    {
        return $this->hasMany('App\HotelWifiIntegrations', 'hotel_id');
    }

    public function brandProduct()
    {
        return $this->hasManyThrough('App\BrandProduct', 'App\Brand', 'hotel_id');
    }

    public function langHotel()
    {
        return $this->hasMany('App\LangHotel', 'id_hotel');
    }

    public function chain()
    {

        return $this->belongsToMany('App\Cadena', 'cadena_hotel', 'id_hotel', 'id_cadena');
    }

    public function staff()
    {
        return $this->belongsToMany('App\HotelStaffHotels', 'hotel_id');
    }

    public function cadena()
    {
        return $this->belongsTo('App\CadenaHotel', 'id_hotel');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_hotels', 'id_hotel', 'id_usuario');
    }

    public function hotelSatisfaction()
    {
        return $this->hasOne('App\HotelSatisfaction', 'id_hotel');
    }

    public function timeZone()
    {
        return $this->belongsTo('App\TimeZone');
    }

    public function hotelReview()
    {
        return $this->hasOne('App\HotelReview', 'id_hotel');
    }

    public function connectionHistory()
    {
        return $this->hasMany('App\ConnectionHistory', 'id_hotel');
    }

    public function userSatisfaction()
    {
        return $this->hasMany('App\Satisfaction', 'id_hotel');
    }
}
