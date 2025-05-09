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
            $table->unsignedBigInteger('id_user')->nullable()->after('id_pelanggan');
    
            $table->foreign('id_user')->references('id')->on('tb_user')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('tb_pelanggan', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });
    }    
};
