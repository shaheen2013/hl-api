<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatisfactionAnswer extends Model
{
    public $timestamps = false;
    protected $table = 'customized_satisfaction_answers';
    protected $guarded = ['id', 'created_at'];
    protected $hidden = [];

    public function question()
    {
        return $this->belongsTo('App\Question', 'survey_question_id');
    }

    public function questionResponse()
    {
        return $this->belongsTo('App\QuestionResponse', 'question_response_id');
    }

    public function satisfaction()
    {
        return $this->belongsTo('App\Satisfaction');
    }
}
