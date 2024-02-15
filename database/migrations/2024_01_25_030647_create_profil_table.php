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
        Schema::create('profil', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('alamat_penerima')->nullable(); 
            $table->string('telepon_penerima')->nullable();
            $table->string('kota_penerima')->nullable();   
            $table->string('pekerjaan')->nullable();  
            $table->string('identitas')->nullable();  
            $table->string('nomor_identitas')->nullable();
            $table->string('negara')->nullable();  
            $table->string('bankaccount')->nullable();
            $table->string('bankname')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil');
    }
};
