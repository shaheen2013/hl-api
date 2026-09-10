<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FullTextSearch;

class Satisfaction extends Model
{
    use FullTextSearch;

    protected $table = 'user_satisfaction';

    protected $hidden = [];
    protected $guarded = [];
    protected $appends = ['rate','comment','date'];

    protected $searchable = ['comentario', 'id_room'];

    public $timestamps = false;

    public function getRateAttribute()
    {
        return $this->attributes['puntuacion'] ;
    }

    public function getCommentAttribute()
    {
        return $this->attributes['comentario'] ;
    }

    public function getDateAttribute()
    {
        return $this->attributes['fecha_creado'] ;
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_usuario');
    }

    public function hotel()
    {
        return $this->belongsTo('App\Hotel', 'id_hotel');
    }

    public function satisfactionAnswer()
    {
        return $this->hasMany('App\SatisfactionAnswer', 'user_satisfaction_id');
    }

    public function userSurvey()
    {
        return $this->hasOne('App\UserSurvey', 'user_satisfaction_id');
    }
}
