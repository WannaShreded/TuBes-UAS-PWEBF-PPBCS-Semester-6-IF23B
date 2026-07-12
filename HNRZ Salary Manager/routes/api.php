<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JabatanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BonusController;
use App\Http\Controllers\Api\PayrollMethodController;
use App\Http\Controllers\Api\EmployeeProfileController;
use App\Http\Controllers\Api\EmployeePayrollMethodController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PayrollHistoryController;
use App\Http\Controllers\Api\StatisticController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', [AuthController::class, 'me']);

    Route::get('/profile', [EmployeeProfileController::class, 'show']);
    Route::put('/profile/password', [EmployeeProfileController::class, 'updatePassword']);
    Route::get('/employee/payroll-methods', [EmployeePayrollMethodController::class, 'index']);
    Route::put('/employee/payroll-method', [EmployeePayrollMethodController::class, 'update']);

});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/statistics', [StatisticController::class, 'index']);
    Route::get('/payroll-histories', [PayrollHistoryController::class, 'index']);
    Route::post('/payroll-histories', [PayrollHistoryController::class, 'store']);
    Route::delete('/payroll-histories/{payrollHistory}', [PayrollHistoryController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('jabatan', JabatanController::class);

    Route::apiResource('bonus', BonusController::class);

    Route::apiResource('payroll-methods', PayrollMethodController::class);

    Route::apiResource('employee', EmployeeController::class);

});
