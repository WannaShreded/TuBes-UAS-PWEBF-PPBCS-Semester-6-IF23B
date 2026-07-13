<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bonus');
            $table->decimal('nominal_bonus', 15, 2);
            $table->enum('jenis_bonus', ['Tetap', 'Variabel']);
            $table->date('periode_bonus'); // disimpan sebagai date, ditampilkan bulan/tahun
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
