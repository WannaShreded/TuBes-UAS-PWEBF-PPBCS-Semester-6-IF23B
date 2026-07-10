<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'bonus_id',
        'payment_method_id',
        'jabatan',
        'gaji_pokok',
        'bonus',
        'total_dibayarkan',
        'payment_method',
        'payment_status',
        'payroll_period',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'gaji_pokok' => 'integer',
        'bonus' => 'integer',
        'total_dibayarkan' => 'integer',
        'payment_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PayrollMethod::class);
    }
}
