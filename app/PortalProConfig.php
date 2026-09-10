<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortalProConfig extends Model
{
    protected $table = "portal_pro_config";
    public $timestamps = false;

    /**
    * @OA\Schema(
    *   schema="PortalProConfig",
    *   type="object",
    *   @OA\Property(property="id", type="integer"),
    *   @OA\Property(property="brand_id", type="integer"),
    *   @OA\Property(property="first_name", type="boolean"),
    *   @OA\Property(property="last_name", type="boolean"),
    *   @OA\Property(property="document_id", type="boolean"),
    *   @OA\Property(property="access_code", type="boolean"),
    *   @OA\Property(property="max_validations", type="integer"),
    *   @OA\Property(property="restrictive", type="boolean"),
    *   @OA\Property(property="premium_code", type="boolean"),
    *   @OA\Property(property="radius_ticket", type="boolean"),
    *   @OA\Property(property="premium_ticket", type="boolean"),
    * )
    */

    protected $fillable = [
        'brand_id',
        'first_name',
        'last_name',
        'document_id',
        'access_code',
        'max_validations',
        'restrictive',
        'premium_code',
        'radius_ticket',
        'premium_ticket',
    ];

    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brand_id');
    }
}
