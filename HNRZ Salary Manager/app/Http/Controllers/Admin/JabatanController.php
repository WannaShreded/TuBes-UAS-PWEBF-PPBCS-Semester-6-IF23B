<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::orderBy('id')->paginate(5);
        return view('admin.jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        return view('admin.jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:jabatans,name',
            'salary'      => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Jabatan::create([
            'name'        => $request->name,
            'salary'      => $request->salary,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('admin.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:jabatans,name,' . $jabatan->id,
            'salary'      => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $jabatan->update([
            'name'        => $request->name,
            'salary'      => $request->salary,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->employees()->exists()) {
            return redirect()->route('admin.jabatan.index')
                ->with('error', "Jabatan '{$jabatan->name}' tidak dapat dihapus karena masih digunakan oleh karyawan.");
        }

        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}
