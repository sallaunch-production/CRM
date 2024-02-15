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
        Schema::create('transaction_success', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_referensi')->nullable();
            $table->string('stamp')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->string('mgisession')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_success');
    }
};
