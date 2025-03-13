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
        Schema::table('tb_user', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pelanggan')->nullable()->change(); // Jadikan nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_user', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pelanggan')->nullable(false)->change(); // Kembalikan ke wajib diisi jika rollback
        });
    }
};
