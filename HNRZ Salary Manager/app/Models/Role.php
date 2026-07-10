<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Role kustom yang meng-extend Role bawaan package spatie/laravel-permission.
 *
 * Ditambahkan agar halaman Role Management (Admin) dapat menggunakan fitur
 * Soft Delete & Recycle Bin, tanpa mengubah perilaku package permission itu
 * sendiri (relasi, guard, dsb tetap mengikuti Role bawaan Spatie).
 */
class Role extends SpatieRole
{
    use SoftDeletes;
}
