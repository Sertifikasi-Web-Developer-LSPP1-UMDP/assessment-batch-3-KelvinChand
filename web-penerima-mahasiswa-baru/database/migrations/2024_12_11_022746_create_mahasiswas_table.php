<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->uuid('IdMahasiswa')->primary();
            $table->foreignUuid('IdUser')
            ->nullable()
            ->references('IdUser')
            ->on('users')
            ->onDelete('cascade');

            $table->foreignUuid('IdJurusan')
            ->nullable()
            ->references('IdJurusan')
            ->on('jurusans')
            ->onDelete('cascade');
            $table->string('status','1')->default(0);
            $table->string('NPM', 20);
            $table->string('alamat',50);
            $table->string('noTelp',12);
            $table->string('jenisKelamin',6);
            $table->string('asalSekolah',30);
            $table->date('tanggalLahir')->nullable();
            $table->date('tanggalBergabung')->default(now());
            $table->date('tanggalKelulusan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
