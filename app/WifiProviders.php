<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/02/2018
 * Time: 12:23
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class WifiProviders extends Model
{
    protected $table = 'wifi_providers';
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];

    public function hotelWifiIntegrations()
    {
        return $this->hasMany('App\HotelWifiIntegrations', 'wifi_id');
    }
}
