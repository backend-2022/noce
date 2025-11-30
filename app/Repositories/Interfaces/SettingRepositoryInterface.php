<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Setting;

interface SettingRepositoryInterface
{
    public function buildQueryWithRelations(): Builder;
    public function getAll(): Collection;
    public function getByKey(string $key): ?Setting;
    public function createOrUpdate(string $key, ?string $value): Setting;
}
