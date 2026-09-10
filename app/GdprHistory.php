<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed hotel_id
 * @property \Illuminate\Support\Carbon created_at
 * @property mixed event
 * @property mixed user_id
 */
class GdprHistory extends Model
{
    protected $table = 'gdpr_history';
    public $timestamps = false;
    protected $guarded = [];
}
