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
        Schema::create('test_transaksi_valid', function (Blueprint $table) {
            $table->increments('no');
            $table->string('id_transaksi')->nullable();
            $table->string('nama_pengirim')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('negara_pengirim')->nullable();
            $table->string('no_referensi')->nullable(); 
            $table->string('identitas')->nullable(); 
            $table->string('nomor_identitas')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('alamat_penerima')->nullable();
            $table->string('telepon_penerima')->nullable();
            $table->string('kota_penerima')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('tujuan_penggunaan_dana')->nullable(); 
            $table->string('id_pos')->nullable();
            $table->datetime('tanggal')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_transaksi_valid');
    }
};
