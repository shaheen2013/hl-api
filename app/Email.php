<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public $timestamps = false;
    protected $table = 'email';
    protected $guarded = ['id'];
    protected $hidden = [];


    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function emailType()
    {
        return $this->belongsTo('App\EmailType');
    }
}
