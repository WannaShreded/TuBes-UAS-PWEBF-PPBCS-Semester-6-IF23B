<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class EmployeeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['view-dashboard', 'view-employees', 'create-employees', 'edit-employees', 'delete-employees'] as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(['view-dashboard', 'view-employees', 'create-employees', 'edit-employees', 'delete-employees']);

        $karyawanRole = Role::firstOrCreate(['name' => 'karyawan']);
        $karyawanRole->syncPermissions(['view-dashboard']);
    }

    public function test_admin_can_view_employee_index_and_create_employee(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/employees');

        $response->assertStatus(200);

        $response = $this->actingAs($admin)->post('/admin/employees', [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'no_telepon' => '081234567890',
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
            'email' => 'budi@example.com',
            'alamat' => 'Jakarta',
            'jabatan' => 'Developer',
            'role' => 'karyawan',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/admin/employees');
        $this->assertDatabaseHas('employees', ['email' => 'budi@example.com']);
    }

    public function test_karyawan_cannot_access_admin_employee_pages(): void
    {
        $employeeUser = User::factory()->create();
        $employeeUser->assignRole('karyawan');

        $response = $this->actingAs($employeeUser)->get('/admin/employees');

        $response->assertStatus(403);
    }
}
