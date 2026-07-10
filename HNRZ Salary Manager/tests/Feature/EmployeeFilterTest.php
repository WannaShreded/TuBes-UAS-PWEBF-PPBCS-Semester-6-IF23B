<?php

use App\Livewire\Admin\EmployeeTable;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('employee role filter matches case-insensitively', function () {
    Employee::factory()->create([
        'nama_lengkap' => 'Alice',
        'role' => 'Karyawan',
        'is_active' => true,
    ]);

    Employee::factory()->create([
        'nama_lengkap' => 'Bob',
        'role' => 'Admin',
        'is_active' => true,
    ]);

    $component = Livewire::test(EmployeeTable::class)
        ->set('role', 'karyawan');

    $items = $component->viewData('items');

    expect($items->pluck('nama_lengkap')->all())->toBe(['Alice']);
});
