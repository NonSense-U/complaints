<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Complaint\ComplaintController;
use App\Http\Controllers\Employee\EmployeeComplaintController;
use App\Http\Controllers\Admin\EmployeeManagementController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Gov\GovController;


Route::prefix ('auth')->group (function(){
Route::post('/register', [RegisterController::class, 'register']);
 Route::post('/verify-otp', [OtpController::class, 'verify']);
  Route::post('/login', [LoginController::class, 'login']);
});
  
Route::middleware('auth:sanctum')->prefix('citizen')->group(function () {
  //Route::put('/updatestatuscomplaints/{id}/status', [ComplaintController::class, 'updateStatus']);
  //show all complaints
    //Route::get('/getallcomplaints', [ComplaintController::class, 'index']);
  //request complaint
    Route::post('/complaints', [ComplaintController::class, 'store']);
    //show one complaint
    Route::get('/getonecomplaint/{id}', [ComplaintController::class, 'show']);
    //get user complaint
    Route::get('/user-complaints', [ComplaintController::class, 'userComplaints']);
  //get Notifications for user
    Route::get('/notifications', [NotificationController::class, 'index']); //done
  //is read or not
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']); //done
    //get Govs name
    Route::get('/govsName', [GovController::class, 'index']);
});




Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
  //show all complaints
    Route::get('/getallcomplaints', [ComplaintController::class, 'index']);
    //Route::get('/complaints', [\App\Http\Controllers\Admin\AdminController::class,'allComplaints']);
    Route::get('/complaints/{id}', [AdminController::class,'showComplaint']);
    Route::get('/stats', [AdminController::class,'stats']);

    // employees management
    Route::get('/employees', [EmployeeManagementController::class,'index']); //done
    Route::post('/employees', [EmployeeManagementController::class,'store']); //done
    Route::put('/employees/{id}', [EmployeeManagementController::class,'update']); //done
    Route::delete('/employees/{id}', [EmployeeManagementController::class,'destroy']); //done

    // user management
    Route::get('/users', [UserManagementController::class,'index']); //done
    Route::put('/users/{id}', [UserManagementController::class,'update']); //done
    Route::delete('/users/{id}', [UserManagementController::class,'destroy']); //done
});





Route::middleware(['auth:sanctum'])->prefix('employee')->group(function () {
    Route::put('/updatestatuscomplaints/{id}/status', [ComplaintController::class, 'updateStatus']);  //done
    Route::get('/complaints', [EmployeeComplaintController::class, 'index']);
    Route::get('/complaints/{id}', [EmployeeComplaintController::class, 'show']);
    Route::post('/complaints/{id}/notes', [EmployeeComplaintController::class, 'addNote']); // employee note
    Route::post('/complaints/{id}/request-info', [EmployeeComplaintController::class, 'requestMoreInfo']);
});