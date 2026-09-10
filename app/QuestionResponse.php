<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionResponse extends Model
{
    protected $table = 'question_response';
    protected $guarded = ['id'];
    protected $hidden = [];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function questionResponseText()
    {
        return $this->hasMany('App\QuestionResponseText');
    }
}
