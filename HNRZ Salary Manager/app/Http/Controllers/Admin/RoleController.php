<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Tampilkan daftar semua role
        public function index()
    {
        $roles = Role::withCount('permissions')->paginate(5);
        return view('admin.roles.index', compact('roles'));
    }

    // Form tambah role baru
    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($perm) {
            // Kelompokkan permission berdasarkan kata terakhir (users, roles, dashboard)
            return explode('-', $perm->name)[1] ?? 'other';
        });
        return view('admin.roles.create', compact('permissions'));
    }

    // Simpan role baru
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|unique:roles,name|max:50',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    // Form edit role
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('-', $perm->name)[1] ?? 'other';
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // Update role
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'        => 'required|string|unique:roles,name,' . $role->id . '|max:50',
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    // Hapus role
    public function destroy(Role $role)
    {
        // Proteksi: jangan hapus role yang masih dipakai user
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh ' . $role->users()->count() . ' user.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
