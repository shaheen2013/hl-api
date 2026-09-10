<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandUrlLanguage extends Model
{
    protected $table = 'brand_url_language';

    protected $visible = [
        'id',
        'brand_id',
        'language_id',
        'url'
    ];

    protected $fillable = [
        'brand_id',
        'language_id',
        'url'
    ];

    public function brandUrlLanguageBrand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function brandUrlLanguageLanguage()
    {
        return $this->belongsTo(Language::class);
    }
}
