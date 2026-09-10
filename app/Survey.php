<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'survey';
    protected $guarded = ['id'];

    public function surveyQuestion()
    {
        return $this->hasMany('App\SurveyQuestion');
    }

    public function userSurveys()
    {
        return $this->hasMany('App\UserSurvey');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function userSatisfaction()
    {
        return $this->hasOne('App\Satisfaction');
    }
}
