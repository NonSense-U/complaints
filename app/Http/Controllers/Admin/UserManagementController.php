<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Services\Admin\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserManagementController extends Controller
{
   
    public function __construct(
        public UserService $userService,
        public UserRepository $userRepo
    ) {}

    public function index()
    {
        return response()->json(User::where('role_id',3)->get());
    }




    public function update(UpdateUserRequest $request, $id)
    {
       try {$user = $this->userService
            ->updatUser($id, $request->validated());

        return response()->json([
            'message' => 'تم تحديث بيانات المستخدم',
            'data' => $user
        ]);}
        catch(ModelNotFoundException $e) {

        return response()->json([
            'message' => 'المستخدم غير موجود'
        ], 404);
    }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);

            return response()->json([
                'message' => 'تم حذف المستخدم بنجاح'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'المستخدم غير موجود'
            ], 404);
        }
    }
}
