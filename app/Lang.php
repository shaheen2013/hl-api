<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
    public $timestamps = false;
    protected $table = 'lang';
    protected $guarded = ['id', 'lang', 'img', 'country', 'system', 'content'];
    protected $visible = ['lang', 'img', 'country'];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->attributes['country'];
    }

    public function categoryText()
    {
        return $this->hasMany('App\CategoryText');
    }

    public function questionText()
    {
        return $this->hasMany('App\QuestionText');
    }
}
