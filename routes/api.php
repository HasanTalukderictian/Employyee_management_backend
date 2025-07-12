<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\DepartmentController;
use App\Http\Controllers\api\DesignationController;
use App\Http\Controllers\api\AdminAuthController;
use App\Http\Controllers\EmployeeController;

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

// Designation

Route::post('/desi', [DesignationController::class, 'store']);


Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout']);


// Employee
Route::post('/add-emplyee', [EmployeeController::class, 'store']);


