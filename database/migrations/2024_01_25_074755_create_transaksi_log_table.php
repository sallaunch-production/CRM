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
        Schema::create('transaksi_log', function (Blueprint $table) {
            $table->increments('no');
            $table->string('bsu')->nullable();
            $table->string('negara_pengirim')->nullable();
            $table->string('id_transaksi')->nullable();
            $table->string('no_referensi')->nullable();
            $table->string('nama_pengirim')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('id_pos')->nullable();
            $table->datetime('tgl')->nullable();
            $table->string('status')->nullable();
            $table->string('proses')->nullable();
            $table->datetime('tgl_valid')->nullable();
            $table->string('user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_log');
    }
};
