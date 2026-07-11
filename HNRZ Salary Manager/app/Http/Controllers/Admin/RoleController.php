<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'permission' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Role::query()->withCount('permissions')
            ->when($validated['search'] ?? null, fn($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when($validated['permission'] ?? null, function ($q, $permission) {
                $q->whereHas('permissions', fn($permissionQuery) => $permissionQuery->where('name', 'like', "%{$permission}%"));
            });

        $roles = $query->paginate(5)->appends($request->query());
        $permissions = Permission::pluck('name')->toArray();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('-', $perm->name)[1] ?? 'other';
        });

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:50',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('-', $perm->name)[1] ?? 'other';
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id . '|max:50',
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh ' . $role->users()->count() . ' user.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dipindahkan ke Recycle Bin.');
    }

    /**
     * Tampilkan daftar role yang berada di Recycle Bin.
     */
    public function trash()
    {
        $roles = Role::onlyTrashed()->withCount('permissions')->paginate(5);

        return view('admin.roles.trash', compact('roles'));
    }

    /**
     * Kembalikan role dari Recycle Bin ke data utama.
     */
    public function restore($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();

        return redirect()->route('admin.roles.trash')
            ->with('success', "Role '{$role->name}' berhasil dipulihkan.");
    }

    /**
     * Hapus role secara permanen dari Recycle Bin.
     */
    public function forceDelete($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $nama = $role->name;
        $role->forceDelete();

        return redirect()->route('admin.roles.trash')
            ->with('success', "Role '{$nama}' berhasil dihapus permanen.");
    }
}
