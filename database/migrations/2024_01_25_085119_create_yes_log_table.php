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
        Schema::create('yes_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user')->nullable();
            $table->string('mgisession')->nullable();
            $table->string('tanggal')->nullable();
            $table->string('no_referensi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yes_log');
    }
};
