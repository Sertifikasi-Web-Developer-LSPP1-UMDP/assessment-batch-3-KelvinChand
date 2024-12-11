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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->uuid('IdPembayaran')->primary();
            $table->foreignUuid('IdUser')
            ->references('IdUser')
            ->on('users')
            ->onDelete('cascade');
            $table->date('tanggalPembayaran')->default(now());
            $table->double('nominalPembayaran');
            $table->string('status',1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
