<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSurvey extends Model
{
    protected $table = 'user_survey';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }

    public function userSurveyQuestionAnswers()
    {
        return $this->hasMany('App\UserSurveyQuestionAnswer');
    }

    public function userSatisfaction()
    {
        return $this->belongsTo('App\Satisfaction');
    }

    public function incidents()
    {
        return $this->hasMany('App\UserSurveyIncident');
    }
}
