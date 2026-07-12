<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JabatanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BonusController;
use App\Http\Controllers\Api\PayrollMethodController;
use App\Http\Controllers\Api\EmployeeProfileController;
use App\Http\Controllers\Api\EmployeePayrollMethodController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', [AuthController::class, 'me']);

    Route::get('/profile', [EmployeeProfileController::class, 'show']);
    Route::get('/employee/payroll-methods', [EmployeePayrollMethodController::class, 'index']);
    Route::put('/employee/payroll-method', [EmployeePayrollMethodController::class, 'update']);

});

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('jabatan', JabatanController::class);

    Route::apiResource('bonus', BonusController::class);

    Route::apiResource('payroll-methods', PayrollMethodController::class);

});
