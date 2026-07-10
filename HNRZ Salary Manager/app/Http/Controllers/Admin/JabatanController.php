<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
        ]);

        $query = Jabatan::query();

        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (! empty($validated['salary_min'])) {
            $query->where('salary', '>=', $validated['salary_min']);
        }

        if (! empty($validated['salary_max'])) {
            $query->where('salary', '<=', $validated['salary_max']);
        }

        $jabatans = $query->orderBy('id')->paginate(5)->appends($request->query());

        return view('admin.jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        return view('admin.jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:jabatans,name',
            'salary' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Jabatan::create([
            'name' => $request->name,
            'salary' => $request->salary,
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
            'name' => 'required|string|max:100|unique:jabatans,name,' . $jabatan->id,
            'salary' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $jabatan->update([
            'name' => $request->name,
            'salary' => $request->salary,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}
