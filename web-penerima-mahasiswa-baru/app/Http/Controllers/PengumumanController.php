<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class PengumumanController extends Controller
{
    /**
     * Register a new pengumuman.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string',
                'deskripsi' => 'required|string',
                'gambar' => 'file|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();
            $gambarPath = null;
            $urlGambar = 'http://127.0.0.1:8000/storage/';

            // Simpan gambar jika ada
            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $gambarPath = $gambar->store('pengumuman', 'public');
            }

            // Simpan pengumuman ke database
            $pengumuman = Pengumuman::create([
                'judulPengumuman' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'gambarPengumuman' => $gambarPath,
                'link' => $urlGambar.$gambarPath
            ]);

            return response()->json([
                'message' => 'Pengumuman berhasil ditambahkan',
                'pengumuman' => $pengumuman,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }


    /**
     * Display a listing of pengumumans.
     */
    public function index()
    {
        try {
            $pengumuman = Pengumuman::all();
            $urlGambar = 'http://127.0.0.1:8000/storage/';
            if (!$pengumuman) {
                return response()->json([
                    'error' => 'Data pengumuman is empty',
                ], 404);
            }
            return response()->json(
                [
                    'message' => 'successfully retrieve data pengumuman',
                    'data' => $pengumuman,
                    'link' => $urlGambar,
                ],
                200
            );
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified pengumuman.
     */
    public function show($id)
    {
        try {
            $pengumuman = pengumuman::find($id);
            $urlGambar = 'http://127.0.0.1:8000/storage/';
            if (!$pengumuman) {
                return response()->json(['error' => 'pengumuman not found'], 404);
            }

            return response()->json(
                [
                    'message' => 'successfully retrieve pengumuman with ' . $id,
                    'data' => $pengumuman,
                    'link' => $urlGambar.$pengumuman->gambarPengumuman,
                ]
                ,
                200
            );
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified pengumuman in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Cari pengumuman berdasarkan ID
            $pengumuman = Pengumuman::find($id);

            if (!$pengumuman) {
                return response()->json(['error' => 'Pengumuman not found'], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'judul' => 'string',
                'deskripsi' => 'string',
                'tanggal' => 'date',
                'gambar' => 'file|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            $gambarPath = $pengumuman->gambarPengumuman;
            $urlGambar = 'http://127.0.0.1:8000/storage/';

            // Update gambar jika ada yang baru
            if ($request->hasFile('gambar')) {
                if ($pengumuman->gambarPengumuman != null) {
                    Storage::disk('public')->delete($pengumuman->gambarPengumuman);
                }
                $gambar = $request->file('gambar');
                $gambarPath = $gambar->store('pengumuman', 'public');
            }

            // Perbarui pengumuman dengan data yang validasi
            $pengumuman->update([
                'judulPengumuman' => $validated['judul'] ?? $pengumuman->judul_pengumuman,
                'deskripsi' => $validated['deskripsi'] ?? $pengumuman->deskripsi,
                'tanggalPengumuman' => $validated['tanggal'] ?? $pengumuman->tanggal_pengumuman,
                'gambarPengumuman' => $gambarPath, // Simpan path gambar
            ]);

            return response()->json([
                'message' => 'Pengumuman berhasil diperbarui',
                'pengumuman' => $pengumuman,
                'link' => $urlGambar.$pengumuman->gambarPengumuman
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified pengumuman from storage.
     */
    public function destroy($id)
    {
        try {
            $pengumuman = pengumuman::find($id);

            if (!$pengumuman) {
                return response()->json(['error' => 'pengumuman not found'], 404);
            }
            $pengumuman->delete();

            return response()->json(['message' => 'pengumuman deleted successfully'], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
