<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  int brand_id
 * @property int id
 */
class Category extends Model
{
    public $timestamps = false;
    protected $table = 'category';
    protected $guarded = ['id'];
    protected $hidden = [];


    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function categoryText()
    {
        return $this->hasMany('App\CategoryText');
    }

    public function question()
    {
        return $this->hasMany('App\Question');
    }
}
