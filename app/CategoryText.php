<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryText extends Model
{
    public $timestamps = false;
    protected $table = 'category_text';
    protected $fillable = ['category_id', 'lang_value', 'text'];
    protected $guarded = ['id'];
    protected $visible = ['id', 'category_id', 'lang_value', 'text'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function lang()
    {
        return $this->belongsTo('App\Lang');
    }
}
