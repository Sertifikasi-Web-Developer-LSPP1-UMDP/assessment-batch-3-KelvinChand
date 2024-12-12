<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;


class MahasiswaController extends Controller
{
    /**
     * Register a new mahasiswa.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'IdUser' => 'required|string',
                'IdJurusan' => 'required|string',
                'alamat' => 'required|string',
                'noTelp' => 'required|string',
                'jenis' => 'required|string',
                'asal' => 'required|string',
                'tanggalLahir' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();
            $tanggalBergabung = now();
            $tanggalKelulusan = now()->addYears(4);

            $jurusan = Jurusan::find($validated['IdJurusan']);
            if (!$jurusan) {
                return response()->json([
                    'message' => 'Jurusan not found',
                ], 404);
            }
            $namaJurusan = $jurusan->namaJurusan;
            if ($namaJurusan == 'Informatika') {
                $kodeJurusan = '25';
            } else if ($namaJurusan == 'Sistem Informasi') {
                $kodeJurusan = '24';
            } else if ($namaJurusan == 'Akuntansi') {
                $kodeJurusan = '23';
            } else if ($namaJurusan == 'Manajemen') {
                $kodeJurusan = '22';
            } else {
                $kodeJurusan = '21';
            }
            $tahunMasuk = $tanggalBergabung->format('Y');
            $tahunWisuda = $tanggalKelulusan->format('Y');
            $hariLahir = \Carbon\Carbon::parse($validated['tanggalLahir'])->format('d');
            $bulanLahir = \Carbon\Carbon::parse($validated['tanggalLahir'])->format('m');
            $npm = $tahunMasuk . $kodeJurusan . $tahunWisuda . $hariLahir . $bulanLahir;

            // Simpan mahasiswa ke database
            $mahasiswa = mahasiswa::create([
                'IdUser' => $validated['IdUser'],
                'IdJurusan' => $validated['IdJurusan'],
                'NPM' => $npm,
                'alamat' => $validated['alamat'],
                'noTelp' => $validated['noTelp'],
                'jenisKelamin' => $validated['jenis'],
                'asalSekolah' => $validated['asal'],
                'tanggalLahir' => $validated['tanggalLahir'],
                'tanggalBergabung' => $tanggalBergabung,
                'tanggalKelulusan' => $tanggalKelulusan,
            ]);

            return response()->json([
                'message' => 'mahasiswa berhasil ditambahkan',
                'mahasiswa' => $mahasiswa,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of mahasiswas.
     */
    public function index()
    {
        try {
            $mahasiswa = mahasiswa::all();
            if ($mahasiswa->isEmpty()) {
                return response()->json([
                    'error' => 'Data mahasiswa is empty',
                ], 404);
            }
            return response()->json([
                'message' => 'Successfully retrieved data mahasiswa',
                'data' => $mahasiswa,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified mahasiswa.
     */
    public function show($id)
    {
        try {
            $mahasiswa = mahasiswa::find($id);
            if (!$mahasiswa) {
                return response()->json(['error' => 'mahasiswa not found'], 404);
            }
            return response()->json([
                'message' => 'Successfully retrieved mahasiswa with ID ' . $id,
                'data' => $mahasiswa,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified mahasiswa in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $mahasiswa = mahasiswa::find($id);

            if (!$mahasiswa) {
                return response()->json(['error' => 'mahasiswa not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'IdJurusan' => 'Uuid',
                'alamat' => 'string',
                'noTelp' => 'string',
                'jenis' => 'string',
                'asal' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();
            $IdJurusan = $validated['IdJurusan'];
            if ($validated['IdJurusan']) {
                $jurusanLama = Jurusan::find($mahasiswa->IdJurusan);
                $jurusanLama->kuota -= 1;
                $jurusanLama->update(['jurusan' => $jurusanLama]);
                $jurusan = Jurusan::find($IdJurusan);
                $jurusan->kuota += 1;
                $jurusan->update(['jurusan' => $jurusan]);
            }

            // Perbarui mahasiswa dengan data yang validasi
            $mahasiswa->update([
                'IdJurusan' => $validated['IdJurusan'] ?? $mahasiswa->IdJurusan,
                'alamat' => $validated['alamat'] ?? $mahasiswa->alamat,
                'noTelp' => $validated['noTelp'] ?? $mahasiswa->noTelp,
                'jenisKelamin' => $validated['jenis'] ?? $mahasiswa->jenisKelamin,
                'asalSekolah' => $validated['asalSekolah'] ?? $mahasiswa->asalSekolah,
            ]);

            return response()->json([
                'message' => 'mahasiswa berhasil diperbarui',
                'mahasiswa' => $mahasiswa,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified mahasiswa from storage.
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = mahasiswa::find($id);

            if (!$mahasiswa) {
                return response()->json(['error' => 'mahasiswa not found'], 404);
            }

            $mahasiswa->delete();

            return response()->json(['message' => 'mahasiswa deleted successfully'], 200);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
