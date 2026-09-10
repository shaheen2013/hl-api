<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'new_device';
    protected $guarded = [];
    public $timestamps = true;
}
