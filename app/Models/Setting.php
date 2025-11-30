<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingsFactory> */
    use HasFactory;
    static string $STORAGE_DIR = "settings";

    protected $fillable = [
        'key',
        'value'
    ];

    public function getRouteKeyName()
    {
        return 'key';
    }

}
