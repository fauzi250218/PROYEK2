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
        Schema::create('tb_tagihan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pelanggan');
            $table->string('bulan', 2); // Contoh: 04
            $table->string('tahun', 4); // Contoh: 2025
            $table->enum('status', ['Lunas', 'Belum Lunas'])->default('Belum Lunas');
            $table->integer('total')->nullable(); // Tagihan dalam angka, bisa ambil dari paket
            $table->timestamps();

            // Foreign key ke tabel pelanggan
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('tb_pelanggan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_tagihan');
    }
};
