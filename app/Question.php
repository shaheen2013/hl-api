<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property  int brand_id
 * @property int category_id
 * @property mixed id
 */
class Question extends Model
{
    public $timestamps = true;
    protected $table = 'question';
    protected $guarded = ['id'];
    protected $hidden = [];


    public function satisfactionAnswers()
    {
        return $this->hasMany('App\SatisfactionAnswers');
    }

    public function questionText()
    {
        return $this->hasMany('App\QuestionText');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function surveyQuestion()
    {
        return $this->hasMany('App\SurveyQuestion');
    }

    public function questionBrand()
    {
        return $this->hasMany('App\QuestionBrand');
    }

    public function questionResponse()
    {
        return $this->hasMany('App\QuestionResponse');
    }
}
