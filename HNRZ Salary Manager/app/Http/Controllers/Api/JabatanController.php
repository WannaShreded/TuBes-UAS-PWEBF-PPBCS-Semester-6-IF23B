<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data jabatan berhasil diambil',
            'data' => Jabatan::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'salary' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $jabatan = Jabatan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil ditambahkan',
            'data' => $jabatan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jabatan $jabatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'salary' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $jabatan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil diperbarui',
            'data' => $jabatan,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jabatan berhasil dihapus',
        ]);
    }
}
