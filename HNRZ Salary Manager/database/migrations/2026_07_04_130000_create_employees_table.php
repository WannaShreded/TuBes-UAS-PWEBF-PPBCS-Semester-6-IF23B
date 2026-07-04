<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammar;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('id_pekerja')->unique();
            $table->string('nik')->unique();
            $table->string('nama_lengkap');
            $table->string('no_telepon');
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('email')->unique();
            $table->text('alamat');
            $table->string('jabatan');
            $table->string('role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
