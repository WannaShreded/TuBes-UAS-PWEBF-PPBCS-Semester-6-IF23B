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
        'jabatan_id',
        'payroll_method_id',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function jabatanRelation()
    {
        return $this->position();
    }

    public function payrollMethod()
    {
        return $this->belongsTo(PayrollMethod::class, 'payroll_method_id');
    }
    public function bonuses()
    {
        return $this->belongsToMany(Bonus::class, 'bonus_employee');
    }

    public function getPositionNameAttribute(): string
    {
        $position = $this->position;

        if ($position) {
            return $position->name;
        }

        if (! empty($this->jabatan)) {
            $legacyPosition = Jabatan::query()->where('name', $this->jabatan)->first();

            if ($legacyPosition) {
                return $legacyPosition->name;
            }
        }

        return 'No Position Assigned';
    }

    public function getSalaryAttribute(): int
    {
        $position = $this->position;

        if ($position) {
            return (int) $position->salary;
        }

        if (! empty($this->jabatan)) {
            $legacyPosition = Jabatan::query()->where('name', $this->jabatan)->first();

            if ($legacyPosition) {
                return (int) $legacyPosition->salary;
            }
        }

        return 0;
    }
}
