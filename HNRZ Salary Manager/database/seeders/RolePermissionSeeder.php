<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'view-dashboard',
            'view-users',
            'edit-users',
            'delete-users',
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-payroll-methods',
            'create-payroll-methods',
            'edit-payroll-methods',
            'delete-payroll-methods',
            'view-employees',
            'create-employees',
            'edit-employees',
            'delete-employees',
            'view-bonuses',
            'create-bonuses',
            'edit-bonuses',
            'delete-bonuses',
            'view-payroll-histories',
            'create-payroll-histories',
            'edit-payroll-histories',
            'delete-payroll-histories',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Role admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        // Role karyawan
        $karyawanRole = Role::firstOrCreate(['name' => 'karyawan']);
        $karyawanRole->syncPermissions(['view-dashboard']);

        // User Admin (AMAN)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        if (! $adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
    }
}
