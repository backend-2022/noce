<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Service;

interface ServiceRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?Service;
    public function create(array $data): Service;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
