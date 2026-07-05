<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('jabatan_id')->nullable()->after('jabatan')->constrained('jabatans')->nullOnDelete();
            $table->foreignId('payroll_method_id')->nullable()->after('jabatan_id')->constrained('payroll_methods')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payroll_method_id');
            $table->dropConstrainedForeignId('jabatan_id');
        });
    }
};
