<?php

namespace App\Repositories\Eloquent;

use App\Models\SiteText;
use App\Repositories\Interfaces\SiteTextRepositoryInterface;
use App\Enums\SiteTextEnums\SiteTextEnum;
use Illuminate\Database\Eloquent\Collection;
class SiteTextRepository implements SiteTextRepositoryInterface
{
    protected $model;

    public function __construct(SiteText $model)
    {
        $this->model = $model;
    }

    public function findByType(SiteTextEnum $type): ?SiteText
    {
        return $this->model->where('type', $type->value)->first();
    }

    public function findAllByType(SiteTextEnum $type): Collection
    {
        return $this->model->where('type', $type->value)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function updateOrCreate(array $conditions, array $data): SiteText
    {
        return $this->model->updateOrCreate($conditions, $data);
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): SiteText
    {
        return $this->model->create($data);
    }

    public function update(SiteText $siteText, array $data): bool
    {
        return $siteText->update($data);
    }

    public function delete(SiteText $siteText): bool
    {
        return $siteText->delete();
    }
}
