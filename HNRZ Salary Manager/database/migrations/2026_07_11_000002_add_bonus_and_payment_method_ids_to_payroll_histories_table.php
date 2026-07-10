<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll_histories', function (Blueprint $table) {
            $table->foreignId('bonus_id')->nullable()->after('employee_id')->constrained('bonuses')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->after('bonus_id')->constrained('payroll_methods')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payroll_histories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
            $table->dropConstrainedForeignId('bonus_id');
        });
    }
};
