<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionResponseText extends Model
{
    protected $table = 'question_response_text';

    protected $fillable = ['question_response_id', 'lang_value', 'text'];
    protected $guarded = ['id'];
    protected $visible = ['id', 'question_response_id', 'lang_value', 'text'];

    public function questionResponse()
    {
        return $this->belongsTo('App\QuestionResponse');
    }

    public function lang()
    {
        return $this->belongsTo('App\Lang');
    }
}
