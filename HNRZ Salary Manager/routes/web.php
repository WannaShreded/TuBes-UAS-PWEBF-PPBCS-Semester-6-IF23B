<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PayrollMethodController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Admin\BonusController;
// =============================================
// Route publik (tanpa login)
// =============================================
Route::get('/', function () {
    return view('welcome');
});

// =============================================
// Route Dashboard - bisa diakses oleh user yang memiliki permission 'view-dashboard'
// =============================================
Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified', 'permission:view-dashboard'])
    ->name('dashboard');

// ── USER MANAGEMENT ──
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/users', [UserController::class, 'index'])
        ->middleware('permission:view-users')
        ->name('users.index');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->middleware('permission:edit-users')
        ->name('users.edit');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('permission:edit-users')
        ->name('users.update');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:delete-users')
        ->name('users.destroy');

    // ── ROLE MANAGEMENT ──
    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:view-roles')
        ->name('roles.index');

    Route::get('/roles/create', [RoleController::class, 'create'])
        ->middleware('permission:create-roles')
        ->name('roles.create');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:create-roles')
        ->name('roles.store');

    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
        ->middleware('permission:edit-roles')
        ->name('roles.edit');

    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:edit-roles')
        ->name('roles.update');

    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('permission:delete-roles')
        ->name('roles.destroy');

    // ── JABATAN MANAGEMENT ──
    Route::get('/jabatan', [JabatanController::class, 'index'])
        ->name('jabatan.index');

    Route::get('/jabatan/create', [JabatanController::class, 'create'])
        ->name('jabatan.create');

    Route::post('/jabatan', [JabatanController::class, 'store'])
        ->name('jabatan.store');

    Route::get('/jabatan/{jabatan}/edit', [JabatanController::class, 'edit'])
        ->name('jabatan.edit');

    Route::put('/jabatan/{jabatan}', [JabatanController::class, 'update'])
        ->name('jabatan.update');

    Route::delete('/jabatan/{jabatan}', [JabatanController::class, 'destroy'])
        ->name('jabatan.destroy');

    // Bonus Management
    Route::get('/bonuses', [BonusController::class, 'index'])
        ->middleware('permission:view-bonuses')
        ->name('bonuses.index');

    Route::get('/bonuses/create', [BonusController::class, 'create'])
        ->middleware('permission:create-bonuses')
        ->name('bonuses.create');

    Route::post('/bonuses', [BonusController::class, 'store'])
        ->middleware('permission:create-bonuses')
        ->name('bonuses.store');

    Route::get('/bonuses/{bonus}/edit', [BonusController::class, 'edit'])
        ->middleware('permission:edit-bonuses')
        ->name('bonuses.edit');

    Route::put('/bonuses/{bonus}', [BonusController::class, 'update'])
        ->middleware('permission:edit-bonuses')
        ->name('bonuses.update');

    Route::delete('/bonuses/{bonus}', [BonusController::class, 'destroy'])
        ->middleware('permission:delete-bonuses')
        ->name('bonuses.destroy');

    // ── PAYROLL METHOD MANAGEMENT (Metode Penggajian) ──
    Route::get('/payroll-methods', [PayrollMethodController::class, 'index'])
        ->middleware('permission:view-payroll-methods')
        ->name('payroll-methods.index');

    Route::get('/payroll-methods/create', [PayrollMethodController::class, 'create'])
        ->middleware('permission:create-payroll-methods')
        ->name('payroll-methods.create');

    Route::post('/payroll-methods', [PayrollMethodController::class, 'store'])
        ->middleware('permission:create-payroll-methods')
        ->name('payroll-methods.store');

    Route::get('/payroll-methods/{payrollMethod}/edit', [PayrollMethodController::class, 'edit'])
        ->middleware('permission:edit-payroll-methods')
        ->name('payroll-methods.edit');

    Route::put('/payroll-methods/{payrollMethod}', [PayrollMethodController::class, 'update'])
        ->middleware('permission:edit-payroll-methods')
        ->name('payroll-methods.update');

    Route::delete('/payroll-methods/{payrollMethod}', [PayrollMethodController::class, 'destroy'])
        ->middleware('permission:delete-payroll-methods')
        ->name('payroll-methods.destroy');

    // ── EMPLOYEE MANAGEMENT ──
    Route::resource('employees', AdminEmployeeController::class);
});

Route::middleware(['auth', 'verified'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/my-position', [EmployeeController::class, 'position'])->name('position');
    Route::get('/payroll-methods', [EmployeeController::class, 'payrollMethods'])->name('payroll-methods.index');
    Route::patch('/payroll-methods', [EmployeeController::class, 'updatePayrollMethod'])->name('payroll-methods.update');
});

// =============================================
// Route Profile (dari Breeze)
// =============================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';
