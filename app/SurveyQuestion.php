<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    public $timestamps = false;
    protected $table = 'survey_question';
    protected $guarded = ['id'];

    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function userSurveyQuestionAnswers()
    {
        return $this->hasMany('App\UserSurveyQuestionAnswer');
    }
}
