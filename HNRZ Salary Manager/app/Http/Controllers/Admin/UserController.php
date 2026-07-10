<?php
// File: app/Http/Controllers/Admin/UserController.php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;


class UserController extends Controller
{
    /**
     * Tampilkan daftar semua user
     */
    public function index()
    {
        $users = User::with('roles')->paginate(5);
        return view('admin.users.index', compact('users'));
    }


    /**
     * Tampilkan form edit user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }


    /**
     * Update data user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|exists:roles,name',
        ]);


        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);


        // Sync role (hapus role lama, beri role baru)
        $user->syncRoles($request->role);


        return redirect()->route('admin.users.index')
                         ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {

        // Cek apakah user yang akan dihapus adalah user yang sedang login
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Data user berhasil dipindahkan ke Recycle Bin.');
    }

    /**
     * Tampilkan daftar user yang berada di Recycle Bin.
     */
    public function trash()
    {
        $users = User::onlyTrashed()->with('roles')->paginate(5);

        return view('admin.users.trash', compact('users'));
    }

    /**
     * Kembalikan user dari Recycle Bin ke data utama.
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.trash')
                         ->with('success', "User '{$user->name}' berhasil dipulihkan.");
    }

    /**
     * Hapus user secara permanen dari Recycle Bin.
     */
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.trash')
                             ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $nama = $user->name;
        $user->forceDelete();

        return redirect()->route('admin.users.trash')
                         ->with('success', "User '{$nama}' berhasil dihapus permanen.");
    }
}
