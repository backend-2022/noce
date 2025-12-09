<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Models\Admin;
use Illuminate\Support\Collection;

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
        // Don't hash password here - let the model's 'hashed' cast handle it
        // The 'hashed' cast in the Admin model will automatically hash the password
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $admin = $this->model->find($id);
        if (!$admin) {
            return false;
        }

        // Don't hash password here - let the model's 'hashed' cast handle it
        // The 'hashed' cast in the Admin model will automatically hash the password
        $admin->fill($data);
        return $admin->save();
    }

    public function delete(int $id): bool
    {
        $admin = $this->model->find($id);
        if ($admin) {
            // Update the email with timestamp before soft deleting to avoid unique constraint issues
            $timestamp = time();
            $admin->email = $admin->email . '-' . $timestamp;
            $admin->save();

            return $admin->delete();
        }
        return false;
    }

    public function updatePassword(int $id, string $password): bool
    {
        $admin = $this->model->find($id);
        if (!$admin) {
            return false;
        }

        // Don't hash password here - let the model's 'hashed' cast handle it
        $admin->password = $password;
        return $admin->save();
    }
}
