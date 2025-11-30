<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Admin;

interface AdminRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?Admin;
    public function getByEmail(string $email): ?Admin;
    public function create(array $data): Admin;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function updatePassword(int $id, string $password): bool;
}
