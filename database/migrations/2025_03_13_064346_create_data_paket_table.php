<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('data_paket', function (Blueprint $table) {
        $table->id();
        $table->string('nama_paket');
        $table->decimal('harga', 10, 2);
        $table->integer('kecepatan'); // Mbps
        $table->string('kategori');
        $table->timestamps();
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_paket');
    }
};
