<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\DepartmentController;
use App\Http\Controllers\api\DesignationController;
use App\Http\Controllers\api\AdminAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeeLeaveController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Department
Route::post('/add-dept', [DepartmentController::class, 'store']);

Route::get('/get-dept', [DepartmentController::class, 'index']);

Route::delete('/del-dept/{id}', [DepartmentController::class, 'destroy']);

Route::post('/edit-dept/{id}', [DepartmentController::class, 'update']);

// Designation

Route::post('/add-desi', [DesignationController::class, 'store']);

Route::get('/get-desi', [DesignationController::class, 'index']);

Route::delete('/del-desi/{id}', [DesignationController::class, 'destroy']);

Route::post('/update-desi/{id}', [DesignationController::class, 'update']);


Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');


// Employee
Route::middleware(['api'])->group(function () {
    Route::post('/add-emplyee', [EmployeeController::class, 'store']);
});

Route::get('/get-emplyee', [EmployeeController::class, 'index']);
Route::delete('/del-emplyee/{id}', [EmployeeController::class, 'destroy']);

Route::get('/get-employee/{id}', [EmployeeController::class, 'show']);
Route::post('/update-emplyee/{id}', [EmployeeController::class, 'update']);

Route::get('/view-emplyee/{id}', [EmployeeController::class, 'show']);


// Salary
Route::post('/add-salary', [SalaryController::class, 'store']);
Route::get('/get-salary/{id}', [SalaryController::class, 'getSalaryByEmployeeId']);
Route::get('/get-salary', [SalaryController::class, 'index']);
Route::delete('/del-salary/{id}', [SalaryController::class, 'destroy']);

// Salary
Route::post('/add-check/{id}', [AttendanceController::class, 'store']);
Route::post('/add-checkout/{id}', [AttendanceController::class, 'checkOut']);

Route::post('/users-store', [UsersController::class, 'store']);
Route::delete('/del-users/{id}', [UsersController::class, 'destroy']);
Route::post('/users-login', [UsersController::class, 'login']);
Route::get('/get-users', [UsersController::class, 'view']);


Route::post('/add-leaves', [EmployeeLeaveController::class, 'store']);
Route::get('/get-leaves', [EmployeeLeaveController::class, 'index']);


// Task



Route::get('/tasks', [TaskController::class, 'index']); 
Route::post('/add-task', [TaskController::class, 'store']); 
Route::put('/tasks/{id}', [TaskController::class, 'update']); 

//admin

Route::get('/get-all-data', [AdminDashboardController::class, 'index']);

Route::post('/users-logout', [UsersController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/test-email', [UsersController::class, 'sendmail']);

