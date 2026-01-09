<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Repositories\OtpRepository;
use App\Http\Resources\UserResource;
use App\Services\Admin\EmployeeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeManagementController extends Controller
{
   
    public function __construct(
        public EmployeeService $employeeService,
        public UserRepository $userRepo,
        public OtpRepository $otpRepo
    ) {}

    public function index()
    {
        return response()->json(User::where('role_id',2)->with('gov')->get());
    }

    public function store(StoreEmployeeRequest $request)
    {
        $user = $this->employeeService->createEmployee($request->validated());
        return response()->json([
            'message' => 'تم إنشاء الحساب، يرجى إدخال رمز التحقق.',
            'user' => new UserResource($user),
            ]
        , 200
        );
    }




    public function update(UpdateEmployeeRequest $request, $id)
    {
       try {$employee = $this->employeeService
            ->updateEmployee($id, $request->validated());

        return response()->json([
            'message' => 'تم تحديث بيانات الموظف',
            'data' => $employee
        ]);}
        catch(ModelNotFoundException $e) {

        return response()->json([
            'message' => 'الموظف غير موجود'
        ], 404);
    }
    }

    public function destroy($id)
    {
        try {
            $this->employeeService->deleteEmployee($id);

            return response()->json([
                'message' => 'تم الحذف بنجاح'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'المستخدم غير موجود'
            ], 404);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400); // 400 Bad Request للخطأ المنطقي
        }
    }
}
