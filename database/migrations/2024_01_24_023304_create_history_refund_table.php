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
        Schema::create('history_refund', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_refund')->nullable();
            $table->string('pop_image')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->text('alasan')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_refund');
    }
};
