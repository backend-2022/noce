<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Models\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AdminRepository implements AdminRepositoryInterface
{
    protected Admin $model;

    public function __construct(Admin $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getById(int $id): ?Admin
    {
        return $this->model->find($id);
    }

    public function getByEmail(string $email): ?Admin
    {
        return $this->model->where('email', $email)->first();
    }

    public function create(array $data): Admin
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->where('id', $id)->delete();
    }

    public function updatePassword(int $id, string $password): bool
    {
        return $this->model->where('id', $id)->update([
            'password' => Hash::make($password)
        ]);
    }
}
