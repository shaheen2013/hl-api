<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFacebook extends Model
{
    protected $table = 'user_facebook';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $visible = ['id', 'name', 'gender', 'age', 'locationID', 'locationName','locale','facebook_img','friends', 'birthday' ];

    protected $appends = ['name', 'friends', 'age'];

    public function getNameAttribute()
    {
        return $this->attributes['nombre'] ;
    }

    public function getFriendsAttribute()
    {
        return $this->attributes['amigos'] ;
    }
    public function getAgeAttribute()
    {
        return $this->attributes['age_max'] ;
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_usuario');
    }
}
