<?php


namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function findByPhoneOrEmail($value)
    {
        return User::where('phone', $value)
                   ->orWhere('email', $value)
                   ->first();
    }

    public function findById(int $id): ?User
    {
        return User::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }
}