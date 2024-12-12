<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class DokumenController extends Controller
{
    /**
     * Register a new dokumen.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'IdMahasiswa' => 'required|string',
                'nama' => 'required|string',
                'dokumen' => 'required|file|mimes:pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();
            $dokumenPath = null;

            // Simpan dokumen jika ada
            if ($request->hasFile('dokumen')) {
                $dokumen = $request->file('dokumen');
                $dokumenPath = $dokumen->store('dokumen', 'public');
                $urlDokumen = 'http://127.0.0.1:8000/storage/';
            }

            // Simpan dokumen ke database
            $dokumen = Dokumen::create([
                'IdMahasiswa' => $validated['IdMahasiswa'],
                'nama' => $validated['nama'],
                'dokumen' => $dokumenPath,
                'link' => $urlDokumen . $dokumenPath,
            ]);

            return response()->json([
                'message' => 'Dokumen berhasil ditambahkan',
                'dokumen' => $dokumen,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of dokumens.
     */
    public function index()
    {
        try {
            $dokumen = Dokumen::all();
            $urlDokumen = 'http://127.0.0.1:8000/storage/';
            if (!$dokumen) {
                return response()->json([
                    'error' => 'Data dokumen is empty',
                ], 404);
            }
            return response()->json(
                [
                    'message' => 'Successfully retrieved data dokumen',
                    'data' => $dokumen,
                    'link' => $urlDokumen,
                ],
                200
            );
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /** * Display the specified dokumen. */
    public function show($id)
    {
        try {
            $dokumen = Dokumen::find($id);
            $urlDokumen = 'http://127.0.0.1:8000/storage/';
            if (!$dokumen) {
                return response()->json(['error' => 'Dokumen not found'], 404);
            }
            return response()->json(['message' => 'Successfully retrieved dokumen with ' . $id, 'data' => $dokumen, 'link' => $urlDokumen . $dokumen->dokumen,], 200);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
    /**
     * Update the specified dokumen in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $dokumen = Dokumen::find($id);

            if (!$dokumen) {
                return response()->json(['error' => 'Dokumen not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'string',
                'dokumen' => 'file|mimes:pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            $dokumenPath = $dokumen->dokumen;
            $urlDokumen = 'http://127.0.0.1:8000/storage/';

            if ($request->hasFile('dokumen')) {
                if ($dokumen->dokumen != null) {
                    Storage::disk('public')->delete($dokumen->dokumen);
                }
                $dokumenFile = $request->file('dokumen');
                $dokumenPath = $dokumenFile->store('dokumen', 'public');
            }

            $dokumen->update([
                'nama' => $validated['nama'] ?? $dokumen->nama,
                'dokumen' => $dokumenPath,
            ]);

            return response()->json([
                'message' => 'Dokumen berhasil diperbarui',
                'dokumen' => $dokumen,
                'link' => $urlDokumen . $dokumen->dokumen
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified dokumen from storage.
     */
    public function destroy($id)
    {
        try {
            $dokumen = Dokumen::find($id);

            if (!$dokumen) {
                return response()->json(['error' => 'Dokumen not found'], 404);
            }

            if ($dokumen->dokumen != null) {
                Storage::disk('public')->delete($dokumen->dokumen);
            }

            $dokumen->delete();

            return response()->json(['message' => 'Dokumen deleted successfully'], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
