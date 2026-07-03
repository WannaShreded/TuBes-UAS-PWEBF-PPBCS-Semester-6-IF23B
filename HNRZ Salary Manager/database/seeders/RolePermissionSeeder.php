<?php
// File: database/seeders/RolePermissionSeeder.php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // Buat permission
        Permission::create(['name' => 'view-dashboard']);
        Permission::create(['name' => 'view-users']);
        Permission::create(['name' => 'edit-users']);


        // Buat role Admin dan beri semua permission
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(['view-dashboard', 'view-users', 'edit-users']);


        // Buat role Manager, hanya boleh akses dashboard
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo(['view-dashboard']);


        // Buat user Admin
        $adminUser = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole('admin');


        // Buat user Manager
        $managerUser = User::create([
            'name'     => 'Manager User',
            'email'    => 'manager@example.com',
            'password' => Hash::make('password'),
        ]);
        $managerUser->assignRole('manager');
    }
}
