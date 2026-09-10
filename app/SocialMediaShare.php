<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialMediaShare extends Model
{
    protected $table = 'new_user_social_media_share';
    protected $guarded = [];
    public $timestamps = false;

    public function userBrand()
    {
        return $this->belongsTo('App\UserBrand');
    }

    public function socialMedia()
    {
        return $this->hasMany('App\SocialMedia');
    }
}
