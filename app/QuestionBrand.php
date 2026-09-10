<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  int brand_id
 * @property int category_id
 * @property mixed id
 */
class QuestionBrand extends Model
{
    public $timestamps = false;
    protected $table = 'question_brand';
    protected $guarded = ['id'];

    public function qestion()
    {
        return $this->belongsTo('App\Question');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
}
