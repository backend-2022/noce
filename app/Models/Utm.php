<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Utm extends Model
{
    protected $fillable = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_id',
        'utm_ads_set_id',
        'utm_ads_set_name',
        'ad_name',
        'ad_id',
    ];
}
