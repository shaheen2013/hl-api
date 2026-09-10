<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceBlacklist extends Model
{
    protected $table = 'device_blacklist';
    public $timestamps = false;
}
