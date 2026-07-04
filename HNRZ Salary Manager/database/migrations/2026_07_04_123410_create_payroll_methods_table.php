<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type');                            // Metode pembayaran, diisi manual: Bank, E-Wallet, Cash, dll
            $table->string('name');                            // Nama metode, mis: "BCA - Gaji Staff"
            $table->string('code')->unique();                  // Kode unik, dibuat otomatis
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_methods');
    }
};
