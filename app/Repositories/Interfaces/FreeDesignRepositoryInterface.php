<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Models\FreeDesign;

interface FreeDesignRepositoryInterface
{
    public function buildQueryWithRelations(): Builder;
    public function getAll(): Collection;
    public function getById(int $id): ?FreeDesign;
    public function create(array $data): FreeDesign;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByStatus(string $status): Collection;
}

