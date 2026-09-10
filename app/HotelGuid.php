<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 22/01/2018
 * Time: 17:40
 */

class HotelGuid extends Model
{
    protected $table = 'hotel_guid';
    public $timestamps = false;

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'id_hotel');
    }
}
