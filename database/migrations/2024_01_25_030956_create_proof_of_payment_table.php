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
        Schema::create('proof_of_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_referensi')->nullable();
            $table->string('pop_image')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->datetime('tanggal_transaksi')->nullable();
            $table->string('nama_client')->nullable();
            $table->string('jenis')->nullable();
            $table->string('ltkl')->nullable();
            $table->string('ltkm')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proof_of_payment');
    }
};
