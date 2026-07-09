<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Jabatan;
use App\Models\PayrollMethod;
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
            'email' => 'budi@example.com',
            'alamat' => 'Jakarta',
            'jabatan' => 'Developer',
            'role' => 'karyawan',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/admin/employees');
        $this->assertDatabaseHas('employees', [
            'email' => 'budi@example.com',
            'nama_bank' => null,
            'nomor_rekening' => null,
        ]);
    }

    public function test_employee_can_update_payroll_method_with_bank_details_when_bank_selected(): void
    {
        $employeeUser = User::factory()->create();
        $employeeUser->assignRole('karyawan');

        $position = Jabatan::create([
            'name' => 'Software Engineer',
            'salary' => 8000000,
        ]);

        $bankMethod = PayrollMethod::create([
            'name' => 'Bank Transfer',
            'type' => 'Bank',
            'description' => 'Transfer to bank account',
            'is_active' => true,
        ]);

        $cashMethod = PayrollMethod::create([
            'name' => 'Cash Payment',
            'type' => 'Cash',
            'description' => 'Receive salary in cash',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'user_id' => $employeeUser->id,
            'id_pekerja' => 'PKR0001',
            'nik' => '3201010101010001',
            'nama_lengkap' => 'Budi Santoso',
            'no_telepon' => '081234567890',
            'email' => 'budi@example.com',
            'alamat' => 'Jakarta',
            'jabatan' => $position->name,
            'jabatan_id' => $position->id,
            'payroll_method_id' => $bankMethod->id,
            'role' => 'karyawan',
        ]);

        $response = $this->actingAs($employeeUser)->patch('/employee/payroll-methods', [
            'nomor_rekening' => '1234567890',
        ]);

        $response->assertRedirect('/employee/payroll-methods');
        $employee->refresh();

        $this->assertEquals($bankMethod->id, $employee->payroll_method_id);
        $this->assertEquals('BRI', $employee->nama_bank);
        $this->assertEquals('1234567890', $employee->nomor_rekening);

        $employee->update(['payroll_method_id' => $cashMethod->id]);

        $response = $this->actingAs($employeeUser)->patch('/employee/payroll-methods', []);

        $response->assertRedirect('/employee/payroll-methods');
        $employee->refresh();

        $this->assertEquals($cashMethod->id, $employee->payroll_method_id);
        $this->assertNull($employee->nama_bank);
        $this->assertNull($employee->nomor_rekening);
        $this->assertNull($employee->nomor_e_wallet);
    }

    public function test_employee_can_update_payroll_method_with_ewallet_details_when_ewallet_selected(): void
    {
        $employeeUser = User::factory()->create();
        $employeeUser->assignRole('karyawan');

        $position = Jabatan::create([
            'name' => 'Software Engineer',
            'salary' => 8000000,
        ]);

        $ewalletMethod = PayrollMethod::create([
            'name' => 'E-Wallet',
            'type' => 'E-Wallet',
            'description' => 'Receive salary to e-wallet',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'user_id' => $employeeUser->id,
            'id_pekerja' => 'PKR0002',
            'nik' => '3201010101010002',
            'nama_lengkap' => 'Budi Santoso',
            'no_telepon' => '081234567890',
            'email' => 'budi2@example.com',
            'alamat' => 'Jakarta',
            'jabatan' => $position->name,
            'jabatan_id' => $position->id,
            'payroll_method_id' => $ewalletMethod->id,
            'role' => 'karyawan',
        ]);

        $response = $this->actingAs($employeeUser)->patch('/employee/payroll-methods', [
            'nomor_e_wallet' => '081234567890',
        ]);

        $response->assertRedirect('/employee/payroll-methods');
        $employee->refresh();

        $this->assertEquals($ewalletMethod->id, $employee->payroll_method_id);
        $this->assertEquals('081234567890', $employee->nomor_e_wallet);
        $this->assertNull($employee->nomor_rekening);
    }

    public function test_karyawan_cannot_access_admin_employee_pages(): void
    {
        $employeeUser = User::factory()->create();
        $employeeUser->assignRole('karyawan');

        $response = $this->actingAs($employeeUser)->get('/admin/employees');

        $response->assertStatus(403);
    }

    public function test_salary_falls_back_to_position_by_name_for_existing_employees_without_jabatan_id(): void
    {
        $position = Jabatan::create([
            'name' => 'Software Engineer',
            'salary' => 8000000,
        ]);

        $employee = Employee::create([
            'id_pekerja' => 'PKR0002',
            'nik' => '3201010101010002',
            'nama_lengkap' => 'Legacy Employee',
            'no_telepon' => '081111111111',
            'nama_bank' => 'BCA',
            'nomor_rekening' => '2222222222',
            'email' => 'legacy@example.com',
            'alamat' => 'Bandung',
            'jabatan' => $position->name,
            'role' => 'karyawan',
        ]);

        $this->assertSame(8000000, $employee->salary);
    }

    public function test_employee_can_view_their_own_position_salary_and_update_payroll_method(): void
    {
        $employeeUser = User::factory()->create();
        $employeeUser->assignRole('karyawan');

        $position = Jabatan::create([
            'name' => 'Software Engineer',
            'salary' => 8000000,
        ]);

        $payrollMethod = PayrollMethod::create([
            'name' => 'Bank Transfer',
            'code' => 'BANK-TRANSFER',
            'type' => 'Bank',
            'description' => 'Transfer to bank account',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'user_id' => $employeeUser->id,
            'id_pekerja' => 'PKR0001',
            'nik' => '3201010101010001',
            'nama_lengkap' => 'Budi Santoso',
            'no_telepon' => '081234567890',
            'nama_bank' => 'BCA',
            'nomor_rekening' => '1234567890',
            'email' => 'budi@example.com',
            'alamat' => 'Jakarta',
            'jabatan' => $position->name,
            'jabatan_id' => $position->id,
            'role' => 'karyawan',
        ]);

        $position->update(['salary' => 9000000]);

        $response = $this->actingAs($employeeUser)->get('/employee/my-position');

        $response->assertOk();
        $response->assertSee('Software Engineer');
        $response->assertSee('Rp 9.000.000');

        $response = $this->actingAs($employeeUser)->patch('/employee/payroll-methods', [
            'payroll_method_id' => $payrollMethod->id,
        ]);

        $response->assertRedirect('/employee/payroll-methods');
        $employee->refresh();
        $this->assertEquals($payrollMethod->id, $employee->payroll_method_id);
    }
}
