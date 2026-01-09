<?php

namespace App\Services\Admin;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    public function __construct(
        protected UserRepository $userRepo
    ) {}


    public function updatUser(int $id, array $data) {
        $user = $this->userRepo->findById($id);
       
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepo->update($id, $data);
    }

    public function deleteUser($id)
    {
        $user = $this->userRepo->findById($id);
        if (!$user) {
            throw new ModelNotFoundException("User not found");
        }
        
        if ($user->role_id != 3) {
            throw new \Exception("لا يمكن حذف هذا المستخدم لأنه ليس مواطنًا");
        }

        $user->delete();
        return true;
    }
}