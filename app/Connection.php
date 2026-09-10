<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $table = 'new_connection';
    protected $guarded = [];
    public $timestamps = true;

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function visit()
    {
        return $this->belongsTo('App\Visit');
    }

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function accessType()
    {
        return $this->belongsTo('App\AccessType');
    }
}
