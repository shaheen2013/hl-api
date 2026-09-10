<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    protected $table = 'user_cupones';
    protected $hidden = [];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'id_usuario');
    }
}
