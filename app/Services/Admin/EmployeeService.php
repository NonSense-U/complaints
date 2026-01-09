<?php

namespace App\Services\Admin;

use App\Jobs\SendOtpJob;
use App\Repositories\UserRepository;
use App\Services\Auth\OtpService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeService
{
    public function __construct(
        protected UserRepository $userRepo,
        protected OtpService $otpService
    ) {}

    public function createEmployee(array $data)
    {
        $data['role_id'] = 2; // Citizen
        $user = $this->userRepo->create($data);

        // $this->otpService->sendOtp($user);

        SendOtpJob::dispatch($user);

        return $user;
    }

    public function updateEmployee(int $id, array $data) {
        $employee = $this->userRepo->findById($id);
       
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepo->update($id, $data);
    }

    public function deleteEmployee($id)
    {
        $employee = $this->userRepo->findById($id);
        if (!$employee) {
            throw new ModelNotFoundException("User not found");
        }

        if ($employee->role_id != 2) {
            throw new \Exception("لا يمكن حذف هذا المستخدم لأنه ليس موظف");
        }

        $employee->delete();
        return true;
    }
}