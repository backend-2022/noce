<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Models\Service;
use Illuminate\Support\Collection;

class ServiceRepository implements ServiceRepositoryInterface
{
    protected Service $model;

    public function __construct(Service $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getById(int $id): ?Service
    {
        return $this->model->find($id);
    }

    public function create(array $data): Service
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        $service = $this->model->find($id);
        if ($service) {
            // Update the name with timestamp before soft deleting to avoid unique constraint issues
            $timestamp = time();
            $service->name = $service->name . '-' . $timestamp;
            $service->save();

            return $service->delete();
        }
        return false;
    }
}
