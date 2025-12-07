<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    Static string $STORAGE_DIR = "backups";
    protected $guarded = [];

}
