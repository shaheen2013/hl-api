<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 11/04/2018
 * Time: 12:23
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $table = 'new_visit';
    protected $guarded = [];
    public $timestamps = false;

    public function userBrand()
    {
        return $this->belongsTo('App\UserBrand');
    }

    public function connections()
    {
        return $this->hasMany(Connection::class);
    }
}
