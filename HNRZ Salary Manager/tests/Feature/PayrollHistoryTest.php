<?php

use App\Models\Bonus;
use App\Models\Employee;
use App\Models\Jabatan;
use App\Models\PayrollMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('admin can create payroll history using employee salary and compute totals', function () {
    $permissions = [
        'view-payroll-histories',
        'create-payroll-histories',
        'edit-payroll-histories',
        'delete-payroll-histories',
    ];

    foreach ($permissions as $permissionName) {
        Permission::firstOrCreate(['name' => $permissionName]);
    }

    $role = Role::create(['name' => 'admin']);
    $role->syncPermissions($permissions);

    $user = User::factory()->create();
    $user->assignRole($role);

    $jabatan = Jabatan::create([
        'name' => 'Manager',
        'salary' => 5000000,
        'description' => 'Manager level',
    ]);

    $employee = Employee::factory()->create([
        'nama_lengkap' => 'Ari',
        'jabatan' => 'Manager',
        'jabatan_id' => $jabatan->id,
        'role' => 'karyawan',
        'is_active' => true,
    ]);

    $payrollMethod = PayrollMethod::create([
        'name' => 'Transfer Bank',
        'type' => 'Bank',
        'description' => 'Transfer',
        'is_active' => true,
    ]);

    $bonus = Bonus::create([
        'nama_bonus' => 'THR',
        'nominal_bonus' => 250000,
        'jenis_bonus' => 'Tetap',
        'periode_bonus' => '2026-07-01',
        'keterangan' => 'Coba',
    ]);

    $this->actingAs($user);

    $response = $this->post(route('admin.payroll-histories.store'), [
        'employee_id' => $employee->id,
        'bonus_id' => $bonus->id,
        'payment_method_id' => $payrollMethod->id,
        'payment_status' => 'Sudah Dibayar',
        'payroll_period' => '2026-07',
        'payment_date' => '2026-07-11',
        'notes' => 'Uji coba',
    ]);

    $response->assertRedirect(route('admin.payroll-histories.index'));

    $this->assertDatabaseHas('payroll_histories', [
        'employee_id' => $employee->id,
        'jabatan' => 'Manager',
        'gaji_pokok' => 5000000,
        'bonus' => 250000,
        'total_dibayarkan' => 5250000,
        'payment_method' => 'Transfer Bank',
        'payment_status' => 'Sudah Dibayar',
        'payroll_period' => '2026-07',
    ]);
});
