<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_pekerja',
        'nik',
        'nama_lengkap',
        'no_telepon',
        'nama_bank',
        'nomor_rekening',
        'email',
        'alamat',
        'jabatan',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
