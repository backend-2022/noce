<?php

namespace App\Models;

use App\Enums\SiteTextEnums\SiteTextEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteText extends Model
{
    use HasFactory;
    static string $STORAGE_DIR = "site-texts";

    protected $fillable = [
        'title',
        'description',
        'image_light',
        'image_dark',
        'type',
        'order',
    ];

    protected $casts = [
        'type' => SiteTextEnum::class,
    ];

    public function scopeOfType($query, SiteTextEnum $type)
    {
        return $query->where('type', $type);
    }

    public function getRouteKeyName()
    {
        return 'title';
    }
}
