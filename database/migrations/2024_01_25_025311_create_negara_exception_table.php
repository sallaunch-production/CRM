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
        Schema::create('negara_exception', function (Blueprint $table) {
            $table->increments('id');
            $table->string('negara')->nullable();
            $table->string('bsu')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->string('no_referensi')->nullable();
            $table->string('maksimum')->nullable();
            $table->string('dari')->nullable();
            $table->string('sampai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negara_exception');
    }
};
