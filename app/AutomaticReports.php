<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomaticReports extends Model
{
    public $timestamps = false;
    protected $table = 'automatic_reports';
    protected $guarded = ['id'];
}
