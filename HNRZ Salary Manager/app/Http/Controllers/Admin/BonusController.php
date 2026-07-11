<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\Employee;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'jenis_bonus' => ['nullable', 'in:Tetap,Variabel'],
            'periode_bonus' => ['nullable', 'date_format:Y-m'],
        ]);

        $query = Bonus::query()
            ->when($validated['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_bonus', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhere('jenis_bonus', 'like', "%{$search}%");
                });
            })
            ->when($validated['jenis_bonus'] ?? null, fn($q, $jenis) => $q->where('jenis_bonus', $jenis))
            ->when($validated['periode_bonus'] ?? null, fn($q, $periode) => $q->where('periode_bonus', 'like', $periode . '%'));

        $bonuses = $query->latest()->paginate(5)->appends($request->query());

        return view('admin.bonuses.index', compact('bonuses'));
    }

    public function create()
    {
        return view('admin.bonuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bonus' => 'required|string|max:100',
            'nominal_bonus' => 'required|numeric|min:0',
            'jenis_bonus' => 'required|in:Tetap,Variabel',
            'periode_bonus' => 'required|date_format:Y-m',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        Bonus::create([
            'nama_bonus' => $request->nama_bonus,
            'nominal_bonus' => $request->nominal_bonus,
            'jenis_bonus' => $request->jenis_bonus,
            'periode_bonus' => $request->periode_bonus . '-01',
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.bonuses.index')
            ->with('success', 'Data bonus berhasil ditambahkan.');
    }

    public function edit(Bonus $bonus)
    {
        return view('admin.bonuses.edit', compact('bonus'));
    }

    public function update(Request $request, Bonus $bonus)
    {
        $request->validate([
            'nama_bonus' => 'required|string|max:100',
            'nominal_bonus' => 'required|numeric|min:0',
            'jenis_bonus' => 'required|in:Tetap,Variabel',
            'periode_bonus' => 'required|date_format:Y-m',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $bonus->update([
            'nama_bonus' => $request->nama_bonus,
            'nominal_bonus' => $request->nominal_bonus,
            'jenis_bonus' => $request->jenis_bonus,
            'periode_bonus' => $request->periode_bonus . '-01',
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.bonuses.index')
            ->with('success', 'Data bonus berhasil diperbarui.');
    }

    public function destroy(Bonus $bonus)
    {
        if ($bonus->employees()->exists()) {
            return redirect()->route('admin.bonuses.index')
                ->with('error', "Bonus \"{$bonus->nama_bonus}\" tidak dapat dihapus karena masih digunakan oleh karyawan.");
        }

        $bonus->delete();

        return redirect()->route('admin.bonuses.index')
            ->with('success', 'Data bonus berhasil dipindahkan ke Recycle Bin.');
    }

    /**
     * Tampilkan daftar bonus yang berada di Recycle Bin.
     */
    public function trash()
    {
        $bonuses = Bonus::onlyTrashed()->orderByDesc('deleted_at')->paginate(5);

        return view('admin.bonuses.trash', compact('bonuses'));
    }

    /**
     * Kembalikan bonus dari Recycle Bin ke data utama.
     */
    public function restore($id)
    {
        $bonus = Bonus::onlyTrashed()->findOrFail($id);
        $bonus->restore();

        return redirect()->route('admin.bonuses.trash')
            ->with('success', "Bonus '{$bonus->nama_bonus}' berhasil dipulihkan.");
    }

    /**
     * Hapus bonus secara permanen dari Recycle Bin.
     */
    public function forceDelete($id)
    {
        $bonus = Bonus::onlyTrashed()->findOrFail($id);
        $nama = $bonus->nama_bonus;
        $bonus->forceDelete();

        return redirect()->route('admin.bonuses.trash')
            ->with('success', "Bonus '{$nama}' berhasil dihapus permanen.");
    }

    public function giveToAll(Bonus $bonus)
    {
        $employeeIds = Employee::pluck('id');

        $bonus->employees()->syncWithoutDetaching($employeeIds);

        return redirect()->route('admin.bonuses.index')
            ->with('success', "Bonus \"{$bonus->nama_bonus}\" berhasil diberikan ke semua karyawan ({$employeeIds->count()} orang).");
    }
}
