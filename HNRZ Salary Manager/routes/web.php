<?php
// File: routes/web.php


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


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
})->middleware(['auth', 'verified', 'permission:view-dashboard'])
  ->name('dashboard');


// =============================================
// Route Admin - hanya bisa diakses Admin
// =============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Halaman daftar user
    Route::get('/users', [UserController::class, 'index'])->name('users.index');


    // Halaman edit user
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');


    // Update data user
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Delete data user
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

});


// =============================================
// Route Profile (dari Breeze)
// =============================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
