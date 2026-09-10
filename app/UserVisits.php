<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVisits extends Model
{
    protected $table = 'users_visits';
    protected $hidden = [];
    public $timestamps = false;
    protected $fillable = ['last_login', 'recurrent', 'num_visits', 'chain_id', 'user_id', 'hotel_id'];


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'hotel_id');
    }

    public function chain()
    {
        return $this->belongsTo('App\Cadena', 'chain_id');
    }
}
