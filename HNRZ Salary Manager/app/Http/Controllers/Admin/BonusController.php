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

        $query = Bonus::query();

        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_bonus', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhere('jenis_bonus', 'like', "%{$search}%");
            });
        }

        if (! empty($validated['jenis_bonus'])) {
            $query->where('jenis_bonus', $validated['jenis_bonus']);
        }

        if (! empty($validated['periode_bonus'])) {
            $query->where('periode_bonus', 'like', $validated['periode_bonus'] . '%');
        }

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
            'keterangan' => 'nullable|string|max:500',
        ]);

        Bonus::create([
            'nama_bonus' => $request->nama_bonus,
            'nominal_bonus' => $request->nominal_bonus,
            'jenis_bonus' => $request->jenis_bonus,
            'periode_bonus' => $request->periode_bonus . '-01',
            'keterangan' => $request->keterangan,
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
            'keterangan' => 'nullable|string|max:500',
        ]);

        $bonus->update([
            'nama_bonus' => $request->nama_bonus,
            'nominal_bonus' => $request->nominal_bonus,
            'jenis_bonus' => $request->jenis_bonus,
            'periode_bonus' => $request->periode_bonus . '-01',
            'keterangan' => $request->keterangan,
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
            ->with('success', 'Data bonus berhasil dihapus.');
    }

    public function giveToAll(Bonus $bonus)
    {
        $employeeIds = Employee::pluck('id');

        $bonus->employees()->syncWithoutDetaching($employeeIds);

        return redirect()->route('admin.bonuses.index')
            ->with('success', "Bonus \"{$bonus->nama_bonus}\" berhasil diberikan ke semua karyawan ({$employeeIds->count()} orang).");
    }
}
