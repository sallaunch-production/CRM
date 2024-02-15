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
        Schema::create('deltawork', function (Blueprint $table) {
            $table->increments('no');
            $table->string('id_transaksi')->nullable();
            $table->string('no_referensi')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->string('operator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deltawork');
    }
};
