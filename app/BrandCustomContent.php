<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 14/03/2019
 * Time: 17:03
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandCustomContent extends Model
{
    protected $table = 'brand_custom_content';
    public $timestamps = false;
    protected $guarded = [];
}
