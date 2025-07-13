<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\DepartmentController;
use App\Http\Controllers\api\DesignationController;
use App\Http\Controllers\api\AdminAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;

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
Route::post('/dept', [DepartmentController::class, 'store']);

Route::get('/get-dept', [DepartmentController::class, 'index']);

Route::delete('/del-dept/{id}', [DepartmentController::class, 'destroy']);

Route::post('/update-dept/{id}', [DepartmentController::class, 'update']);

// Designation

Route::post('/desi', [DesignationController::class, 'store']);

Route::get('/get-desi', [DesignationController::class, 'index']);

Route::delete('/del-desi/{id}', [DesignationController::class, 'destroy']);

Route::post('/update-desi/{id}', [DesignationController::class, 'update']);


Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout']);


// Employee
Route::post('/add-emplyee', [EmployeeController::class, 'store']);

Route::get('/get-emplyee', [EmployeeController::class, 'index']);
Route::delete('/del-emplyee/{id}', [EmployeeController::class, 'destroy']);

Route::post('/update-emplyee/{id}', [EmployeeController::class, 'update']);

Route::get('/view-emplyee/{id}', [EmployeeController::class, 'show']);

// Leave

Route::post('/add-leave', [LeaveController::class, 'store']);


Route::get('/get-leave/{id}', [LeaveController::class, 'show']);
Route::delete('/del-leave/{id}', [LeaveController::class, 'destroy']);



