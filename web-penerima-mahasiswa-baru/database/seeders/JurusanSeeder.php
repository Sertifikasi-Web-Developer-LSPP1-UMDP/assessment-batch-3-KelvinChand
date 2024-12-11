<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan 5 jurusan secara manual
        Jurusan::create(
            [
                'namaJurusan' => 'Informatika',
                'Fakultas' => 'Fakultas Ilmu Rekayasan Komputer',
                'kuota' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        Jurusan::create([
                'namaJurusan' => 'Sistem Informasi',
                'Fakultas' => 'Fakultas Ilmu Rekayasa Komputer',
                'kuota' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        Jurusan::create([
                'namaJurusan' => 'Akuntansi',
                'Fakultas' => 'Fakultas Ekonomi',
                'kuota' => 70,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        Jurusan::create([
                'namaJurusan' => 'Manajemen',
                'Fakultas' => 'Fakultas Ekonomi',
                'kuota' => 80,
                'created_at' => now(),
                'updated_at' => now(),
        ]);
        Jurusan::create([
                'namaJurusan' => 'Teknik Industri',
                'Fakultas' => 'Fakultas Teknik',
                'kuota' => 90,
                'created_at' => now(),
                'updated_at' => now(),
        ]);
    }
}

