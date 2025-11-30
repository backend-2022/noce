<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use App\Models\City;

interface CityRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?City;
    public function create(array $data): City;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
