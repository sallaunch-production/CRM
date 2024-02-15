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
        Schema::create('kode_pos_insentif', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nopend')->nullable();
            $table->string('nama')->nullable();
            $table->integer('regional')->nullable();
            $table->string('kprk')->nullable();
            $table->string('kota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_pos_insentif');
    }
};
