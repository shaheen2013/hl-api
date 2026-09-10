<?php

/**
 * Created by PhpStorm.
 * User: Ricardo
 * Date: 11/04/2018
 * Time: 12:23
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGuid extends Model
{
    protected $table = 'user_guid';
    public $timestamps = false;
    protected $fillable = ['id_usuario', 'guid'];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_usuario');
    }
}
