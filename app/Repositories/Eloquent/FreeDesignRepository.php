<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\FreeDesignRepositoryInterface;
use App\Models\FreeDesign;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FreeDesignRepository implements FreeDesignRepositoryInterface
{
    protected FreeDesign $model;

    public function __construct(FreeDesign $model)
    {
        $this->model = $model;
    }

    public function buildQueryWithRelations(): Builder
    {
        return $this->model->with(['city', 'service']);
    }

    public function getAll(): Collection
    {
        return $this->buildQueryWithRelations()->get();
    }

    public function getById(int $id): ?FreeDesign
    {
        return $this->buildQueryWithRelations()->find($id);
    }

    public function create(array $data): FreeDesign
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $freeDesign = $this->model->find($id);
        if ($freeDesign) {
            return $freeDesign->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $freeDesign = $this->model->find($id);
        if ($freeDesign) {
            return $freeDesign->delete();
        }
        return false;
    }

    public function getByStatus(string $status): Collection
    {
        return $this->buildQueryWithRelations()
            ->where('status', $status)
            ->get();
    }
}

