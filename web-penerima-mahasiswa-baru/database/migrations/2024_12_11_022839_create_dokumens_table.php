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
        Schema::create('dokumens', function (Blueprint $table) {
            $table->uuid('IdDokumen')->primary();
            $table->foreignUuid('IdMahasiswa')
            ->references('IdMahasiswa')
            ->on('mahasiswas')
            ->onDelete('cascade');
            $table->string('namaDokumen',30);
            $table->string('dokumenPath')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
