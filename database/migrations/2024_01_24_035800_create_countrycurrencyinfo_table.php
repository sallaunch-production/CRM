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
        Schema::create('countrycurrencyinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('countryCode')->nullable();
            $table->string('baseCurrency')->nullable();
            $table->string('localCurrency')->nullable();
            $table->string('receiveCurrency')->nullable();
            $table->integer('indicativeRateAvailable')->nullable();
            $table->string('deliveryOption ')->nullable();
            $table->datetime('tanggal  ')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countrycurrencyinfo');
    }
};
