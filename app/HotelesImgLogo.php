<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 22/01/2018
 * Time: 17:34
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelesImgLogo extends Model
{
    protected $table = 'hoteles_img_logo';
    public $timestamps = false;

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'id_hotel');
    }
}
