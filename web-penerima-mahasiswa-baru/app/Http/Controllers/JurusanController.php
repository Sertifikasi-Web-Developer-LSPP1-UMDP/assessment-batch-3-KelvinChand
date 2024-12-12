<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class JurusanController extends Controller
{
    /**
     * Register a new jurusan.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string',
                'fakultas' => 'required|string',
                'kuota' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            // Simpan jurusan ke database
            $jurusan = Jurusan::create([
                'namaJurusan' => $validated['nama'],
                'fakultas' => $validated['fakultas'],
                'kuota' => $validated['kuota'],
            ]);

            return response()->json([
                'message' => 'Jurusan berhasil ditambahkan',
                'jurusan' => $jurusan,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of jurusans.
     */
    public function index()
    {
        try {
            $jurusan = Jurusan::all();
            if ($jurusan->isEmpty()) {
                return response()->json([
                    'error' => 'Data jurusan is empty',
                ], 404);
            }
            return response()->json([
                'message' => 'Successfully retrieved data jurusan',
                'data' => $jurusan,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified jurusan.
     */
    public function show($id)
    {
        try {
            $jurusan = Jurusan::find($id);
            if (!$jurusan) {
                return response()->json(['error' => 'Jurusan not found'], 404);
            }
            return response()->json([
                'message' => 'Successfully retrieved jurusan with ID ' . $id,
                'data' => $jurusan,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified jurusan in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $jurusan = Jurusan::find($id);

            if (!$jurusan) {
                return response()->json(['error' => 'Jurusan not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'string',
                'fakultas' => 'string',
                'kuota' => 'integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            // Perbarui jurusan dengan data yang validasi
            $jurusan->update([
                'namaJurusan' => $validated['nama'] ?? $jurusan->namaJurusan,
                'fakultas' => $validated['fakultas'] ?? $jurusan->fakultas,
                'kuota' => $validated['kuota'] ?? $jurusan->kuota,
            ]);

            return response()->json([
                'message' => 'Jurusan berhasil diperbarui',
                'jurusan' => $jurusan,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified jurusan from storage.
     */
    public function destroy($id)
    {
        try {
            $jurusan = Jurusan::find($id);

            if (!$jurusan) {
                return response()->json(['error' => 'Jurusan not found'], 404);
            }

            $jurusan->delete();

            return response()->json(['message' => 'Jurusan deleted successfully'], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
