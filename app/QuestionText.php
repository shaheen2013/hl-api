<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionText extends Model
{
    public $timestamps = false;
    protected $table = 'question_text';
    protected $fillable = ['question_id', 'lang_value', 'text'];
    protected $guarded = ['id'];
    protected $visible = ['id', 'question_id', 'lang_value', 'text'];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function lang()
    {
        return $this->belongsTo('App\Lang');
    }
}
