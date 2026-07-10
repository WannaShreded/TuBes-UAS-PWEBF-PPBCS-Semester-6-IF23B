<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nama_bank')->nullable()->change();
            $table->string('nomor_rekening')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nama_bank')->nullable(false)->change();
            $table->string('nomor_rekening')->nullable(false)->change();
        });
    }
};
