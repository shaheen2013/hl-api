<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSurveyQuestionAnswer extends Model
{
    protected $table = 'user_survey_question_answer';
    protected $guarded = ['id'];

    public function userSurvey()
    {
        return $this->belongsTo('App\UserSurvey');
    }

    public function surveyQuestion()
    {
        return $this->belongsTo('App\SurveyQuestion');
    }
}
