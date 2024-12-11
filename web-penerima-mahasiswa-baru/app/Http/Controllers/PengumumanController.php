<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
                'gambar' => 'sometimes|file|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            // Buat folder 'pengumuman' jika belum ada
            $folderPath = public_path('storage/pengumuman');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $gambarPath = null;

            // Simpan gambar jika ada
            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $gambarPath = $gambar->store('pengumuman', 'public');
                $urlGambar = 'http://127.0.0.1:8000/storage/';
            }

            // Simpan pengumuman ke database
            $pengumuman = Pengumuman::create([
                'judulPengumuman' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'gambarPengumuman' => $urlGambar.$gambarPath, // Simpan path gambar
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
            if (!$pengumuman) {
                return response()->json([
                    'error' => 'Data pengumuman is empty',
                ], 404);
            }
            return response()->json(
                [
                    'message' => 'successfully retrieve data pengumuman',
                    'data' => $pengumuman,
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

            if (!$pengumuman) {
                return response()->json(['error' => 'pengumuman not found'], 404);
            }

            return response()->json(
                [
                    'message' => 'successfully retrieve pengumuman with ' . $id,
                    'data' => $pengumuman,
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

            $pengumuman = pengumuman::find($id);

            if (!$pengumuman) {
                return response()->json(['error' => 'pengumuman not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:pengumumans,email,',
                'password' => 'sometimes|required|string|min:8|confirmed',
                'roles' => 'sometimes|in:pengumuman,admin'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $validated = $validator->validated();

            $pengumuman->update([
                'name' => $validated['name'] ?? $pengumuman->name,
                'email' => $validated['email'] ?? $pengumuman->email,
                'password' => isset($validated['password']),
                'roles' => $validated['roles'] ?? $pengumuman->roles,
            ]);

            return response()->json([
                'message' => 'pengumuman updated successfully',
                'pengumuman' => $pengumuman
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
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

            if ($pengumuman->status != 2) {
                return response()->json(['error' => 'pengumuman account is still active'], 400);
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
