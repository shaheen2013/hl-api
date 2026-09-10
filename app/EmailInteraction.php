<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailInteraction extends Model
{
    public $timestamps = false;
    protected $table = 'email_interaction';
    protected $guarded = ['id'];
    protected $hidden = [];


    public function email()
    {
        return $this->belongsTo('App\Email');
    }
}
