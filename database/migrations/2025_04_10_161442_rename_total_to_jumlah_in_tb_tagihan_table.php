<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tb_tagihan', function (Blueprint $table) {
            $table->renameColumn('total', 'jumlah');
        });
    }

    public function down(): void
    {
        Schema::table('tb_tagihan', function (Blueprint $table) {
            $table->renameColumn('jumlah', 'total');
        });
    }
};