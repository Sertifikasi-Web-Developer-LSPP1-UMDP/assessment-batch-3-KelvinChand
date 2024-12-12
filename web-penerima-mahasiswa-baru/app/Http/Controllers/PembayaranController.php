<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    /**
     * Register a new pembayaran.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'IdUser' => 'required|string',
                'nominalPembayaran' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            // Simpan pembayaran ke database
            $pembayaran = pembayaran::create([
                'IdUser' => $validated['IdUser'],
                'nominalPembayaran' => $validated['nominalPembayaran'],
            ]);

            return response()->json([
                'message' => 'pembayaran berhasil ditambahkan',
                'pembayaran' => $pembayaran,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of pembayarans.
     */
    public function index()
    {
        try {
            $pembayaran = pembayaran::all();
            if ($pembayaran->isEmpty()) {
                return response()->json([
                    'error' => 'Data pembayaran is empty',
                ], 404);
            }
            return response()->json([
                'message' => 'Successfully retrieved data pembayaran',
                'data' => $pembayaran,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified pembayaran.
     */
    public function show($id)
    {
        try {
            $pembayaran = pembayaran::find($id);
            if (!$pembayaran) {
                return response()->json(['error' => 'pembayaran not found'], 404);
            }
            return response()->json([
                'message' => 'Successfully retrieved pembayaran with ID ' . $id,
                'data' => $pembayaran,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified pembayaran in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $pembayaran = pembayaran::find($id);

            if (!$pembayaran) {
                return response()->json(['error' => 'pembayaran not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nominalPembayaran' => 'required|numeric',
                'status' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();

            // Perbarui pembayaran dengan data yang validasi
            $pembayaran->update([
                'nominalPembayaran' => $validated['nominalPembayaran'] ?? $pembayaran->nominalPembayaran,
                'status' => $validated['status'] ?? $pembayaran->status,
            ]);

            return response()->json([
                'message' => 'pembayaran berhasil diperbarui',
                'pembayaran' => $pembayaran,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified pembayaran from storage.
     */
    public function destroy($id)
    {
        try {
            $pembayaran = pembayaran::find($id);

            if (!$pembayaran) {
                return response()->json(['error' => 'pembayaran not found'], 404);
            }

            $pembayaran->delete();

            return response()->json(['message' => 'pembayaran deleted successfully'], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
