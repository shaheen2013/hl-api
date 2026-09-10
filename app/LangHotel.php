<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 22/01/2018
 * Time: 11:20
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class LangHotel extends Model
{
    protected $table = 'lang_hotel';
    public $timestamps = false;

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'id_hotel');
    }
}
