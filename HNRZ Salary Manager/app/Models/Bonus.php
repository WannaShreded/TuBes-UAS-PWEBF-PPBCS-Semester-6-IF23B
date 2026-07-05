<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $fillable = [
        'nama_bonus',
        'nominal_bonus',
        'jenis_bonus',
        'periode_bonus',
        'keterangan',
    ];

    protected $casts = [
        'periode_bonus' => 'date',
        'nominal_bonus' => 'decimal:2',
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'bonus_employee');
    }

    // Accessor untuk menampilkan periode dalam format Bulan Tahun
    public function getPeriodeLabelAttribute(): string
    {
        return $this->periode_bonus->translatedFormat('F Y');
    }

    // Accessor untuk menampilkan nominal dalam format Rupiah
    public function getNominalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal_bonus, 0, ',', '.');
    }
}
