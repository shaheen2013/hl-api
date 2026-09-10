<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FullTextSearch;

/**
 * @property mixed email
 * @property string nombre
 * @property mixed sexo
 * @property mixed location
 * @property bool|string lang
 * @property mixed sendex
 * @property mixed fecha_nacimiento
 * @property mixed email_result
 * @property static created
 * @property string pais
 */
class User extends Model
{
    use FullTextSearch;

    protected $table = 'users';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name', 'password', 'nombre', 'first_name', 'last_name', 'unsubscribed', 'sendex', 'email_result', 'fecha_nacimiento', 'sexo', 'location', 'lang', 'pais', 'created', 'generation', 'user_card', 'data'
    ];

    protected $visible = [
        'id', 'email', 'name', 'first_name', 'last_name', 'location', 'country', 'lang', 'birthday', 'gender', 'email_result', 'unsubscribed', 'userFacebook', 'sendex', 'userGuid', 'user_card', 'generation'
    ];

    protected $appends = ['name', 'birthday', 'gender', 'country'];
    protected $searchable = ['nombre, email'];

    protected $casts = [
        'data' => 'array',
    ];

    public function getNameAttribute()
    {
        return $this->attributes['nombre'];
    }

    public function getBirthdayAttribute()
    {
        return $this->attributes['fecha_nacimiento'];
    }

    public function getGenderAttribute()
    {
        return $this->attributes['sexo'];
    }

    public function getCountryAttribute()
    {
        return $this->attributes['pais'];
    }

    public function userGuid()
    {
        return $this->hasOne('App\UserGuid', 'id_usuario');
    }

    public function userVisits()
    {
        return $this->hasOne('App\UserVisits', 'user_id');
    }

    public function userFacebook()
    {
        return $this->hasOne('App\UserFacebook', 'id_usuario');
    }

    public function visits()
    {
        return $this->hasMany('App\UserVisits', 'user_id');
    }

    public function userHotels()
    {
        return $this->hasMany('App\UserHotel', 'id_usuario');
    }

    public function language()
    {
        return $this->hasOne(Lang::class, 'lang', 'lang');
    }
}
