<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BonusController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data bonus berhasil diambil',
            'data' => Bonus::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bonus' => 'required|string|max:100',
            'nominal_bonus' => 'required|numeric|min:0',
            'jenis_bonus' => 'required|in:Tetap,Variabel',
            'periode_bonus' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $bonus = Bonus::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Bonus berhasil ditambahkan',
            'data' => $bonus,
        ], 201);
    }

    public function show(Bonus $bonus)
    {
        return response()->json([
            'success' => true,
            'data' => $bonus,
        ]);
    }

    //

    public function update(Request $request, $id)
    {
        $bonus = Bonus::find($id);

        if (!$bonus) {
            return response()->json([
                'message' => 'Bonus tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama_bonus' => 'required|string|max:100',
            'nominal_bonus' => 'required|numeric|min:0',
            'jenis_bonus' => 'required|in:Tetap,Variabel',
            'periode_bonus' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $bonus->update($validated);

        return response()->json([
            'success' => true,
            'data' => $bonus->fresh()
        ]);
    }

    // public function destroy(Bonus $bonus)
    // {
    //     Log::info('Bonus yang akan dihapus', [
    //         'id' => $bonus->id,
    //         'exists' => $bonus->exists,
    //     ]);

    //     if ($bonus->employees()->exists()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Bonus masih digunakan oleh karyawan.'
    //         ], 422);
    //     }

    //     $deleted = $bonus->delete();

    //     Log::info('Hasil delete', [
    //         'deleted' => $deleted,
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Bonus berhasil dihapus',
    //     ]);
    // }

    public function destroy($id)
    {
        $bonus = Bonus::find($id);

        if (!$bonus) {
            return response()->json([
                'message' => 'Tidak ditemukan'
            ], 404);
        }

        $bonus->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
