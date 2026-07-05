<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\Employee;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    public function index()
    {
        $bonuses = Bonus::latest()->paginate(5);
        return view('admin.bonuses.index', compact('bonuses'));
    }

    public function create()
    {
        return view('admin.bonuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bonus'    => 'required|string|max:100',
            'nominal_bonus' => 'required|numeric|min:0',
            'jenis_bonus'   => 'required|in:Tetap,Variabel',
            'periode_bonus' => 'required|date_format:Y-m',
            'keterangan'    => 'nullable|string|max:500',
        ]);

        Bonus::create([
            'nama_bonus'    => $request->nama_bonus,
            'nominal_bonus' => $request->nominal_bonus,
            'jenis_bonus'   => $request->jenis_bonus,
            // input type="month" menghasilkan format Y-m, tambahkan -01 agar valid sebagai date
            'periode_bonus' => $request->periode_bonus . '-01',
            'keterangan'    => $request->keterangan,
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
            'nama_bonus'    => 'required|string|max:100',
            'nominal_bonus' => 'required|numeric|min:0',
            'jenis_bonus'   => 'required|in:Tetap,Variabel',
            'periode_bonus' => 'required|date_format:Y-m',
            'keterangan'    => 'nullable|string|max:500',
        ]);

        $bonus->update([
            'nama_bonus'    => $request->nama_bonus,
            'nominal_bonus' => $request->nominal_bonus,
            'jenis_bonus'   => $request->jenis_bonus,
            'periode_bonus' => $request->periode_bonus . '-01',
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->route('admin.bonuses.index')
            ->with('success', 'Data bonus berhasil diperbarui.');
    }

    public function destroy(Bonus $bonus)
    {
        $bonus->delete();

        return redirect()->route('admin.bonuses.index')
            ->with('success', 'Data bonus berhasil dihapus.');
    }
    public function giveToAll(Bonus $bonus)
    {
        $employeeIds = Employee::pluck('id');

        // syncWithoutDetaching supaya karyawan yang sudah pernah dapat bonus ini tidak dobel
        $bonus->employees()->syncWithoutDetaching($employeeIds);

        return redirect()->route('admin.bonuses.index')
            ->with('success', "Bonus \"{$bonus->nama_bonus}\" berhasil diberikan ke semua karyawan ({$employeeIds->count()} orang).");
    }
}
