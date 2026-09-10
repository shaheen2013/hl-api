<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 05/02/2018
 * Time: 12:13
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HotelWifiIntegrations extends Model
{
    protected $table = 'hotel_wifi_integrations';
    public $timestamps = false;

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'hotel_id');
    }

    public function wifiIntegration()
    {
        return $this->belongsTo('App\wifi_integration', 'wifi_id');
    }
}
