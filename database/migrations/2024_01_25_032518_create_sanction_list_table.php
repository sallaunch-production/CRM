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
        Schema::create('sanction_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama')->nullable(); 
            $table->text('alias')->nullable();
            $table->string('no_ktp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nohp')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('negara')->nullable();
            $table->text('keterangan')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->string('hapus')->nullable();
            $table->text('ubah')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanction_list');
    }
};
