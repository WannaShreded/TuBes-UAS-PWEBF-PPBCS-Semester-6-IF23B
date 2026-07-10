<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'salary'])]
class Jabatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'salary', 'description'];

    protected $casts = [
        'salary' => 'integer',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'jabatan_id');
    }
}
