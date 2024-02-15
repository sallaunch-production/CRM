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
        Schema::create('currencyinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currencyCode')->nullable();
            $table->string('currencyName')->nullable();
            $table->integer('currencyPrecision')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencyinfo');
    }
};
