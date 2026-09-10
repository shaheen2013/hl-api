<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBrand extends Model
{
    protected $table = 'new_user_brand';
    public $timestamps = false;

    protected $fillable = ['unsubscribed', 'subscriptions'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function connections()
    {
        return $this->hasManyThrough(Connection::class, Visit::class);
    }
}
