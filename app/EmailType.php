<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailType extends Model
{
    public $timestamps = false;
    protected $table = 'email_type';
    protected $guarded = ['id'];
    protected $hidden = [];

    public function interactionEmail()
    {
        return $this->hasMany('App\InteractionEmail');
    }
}
