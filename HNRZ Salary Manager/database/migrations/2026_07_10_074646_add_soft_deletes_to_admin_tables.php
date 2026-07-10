<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom `deleted_at` pada tabel-tabel yang digunakan oleh
     * halaman CRUD Admin agar mendukung fitur Soft Delete & Recycle Bin.
     * Tabel/fitur milik Karyawan (mis. tidak ada tabel tambahan di luar ini)
     * tidak disentuh sama sekali.
     */
    public function up(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('bonuses', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('payroll_methods', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table(config('permission.table_names')['roles'], function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('bonuses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('payroll_methods', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table(config('permission.table_names')['roles'], function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};