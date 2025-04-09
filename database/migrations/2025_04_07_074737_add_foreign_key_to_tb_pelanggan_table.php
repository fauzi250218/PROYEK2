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
        Schema::table('tb_pelanggan', function (Blueprint $table) {
            $table->unsignedBigInteger('paket')->change(); // pastikan unsigned
            $table->foreign('paket')->references('id')->on('data_paket')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('tb_pelanggan', function (Blueprint $table) {
            $table->dropForeign(['paket']);
        });
    }    
};
