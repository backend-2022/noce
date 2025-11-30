<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SettingRepository implements SettingRepositoryInterface
{
    protected Setting $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    public function buildQueryWithRelations(): Builder
    {
        return $this->model->newQuery();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getByKey(string $key): ?Setting
    {
        return $this->model->where('key',$key)->first();
    }

    public function createOrUpdate(string $key, ?string $value): Setting
    {
        return $this->model->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
