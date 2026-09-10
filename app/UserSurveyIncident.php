<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSurveyIncident extends Model
{
    protected $table = 'user_survey_incident';
    protected $guarded = ['id'];

    public function hotelStaff()
    {
        return $this->belongsTo('App\HotelStaff');
    }

    public function userSurvey()
    {
        return $this->belongsTo('App\UserSurvey');
    }

    public function hotel()
    {
        return $this->belongsTo('App\Hotel');
    }
}
