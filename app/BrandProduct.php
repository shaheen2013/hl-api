<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yadakhov\InsertOnDuplicateKey;

class BrandProduct extends Model
{
    use InsertOnDuplicateKey;

    public $timestamps = true;

    protected $table = 'brand_product';
    protected $fillable = ['brand_id', 'product_id', 'active', 'created_at', 'updated_at', 'config'];
    protected $visible = ['id', 'product_id', 'active', 'name', 'active_by_name', 'config'];
    public $appends = ['name', 'active_by_name'];

    protected $casts = [
        'config' => 'json',
    ];

    public function getNameAttribute()
    {
        return $this->product->name;
    }

    public function getActiveByNameAttribute()
    {
        $name = $this->product->name;
        return [$name => $this->attributes['active']];
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function brandProductConfig()
    {
        return $this->hasMany('App\BrandProductConfig');
    }
}
