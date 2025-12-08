<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Models\City;
use Illuminate\Support\Collection;

class CityRepository implements CityRepositoryInterface
{
    protected City $model;

    public function __construct(City $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getById(int $id): ?City
    {
        return $this->model->find($id);
    }

    public function create(array $data): City
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        $city = $this->model->find($id);
        if ($city) {
            // Update the name with timestamp before soft deleting to avoid unique constraint issues
            $timestamp = time();
            $city->name = $city->name . '-' . $timestamp;
            $city->save();

            return $city->delete();
        }
        return false;
    }
}
