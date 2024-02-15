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
        Schema::create('countryinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('countryCode')->nullable();
            $table->string('countryName')->nullable();
            $table->string('countryLegacyCode')->nullable();
            $table->string('sendActive')->nullable();
            $table->string('receiveActive')->nullable();
            $table->string('directedSendCountry')->nullable();
            $table->string('mgDirectedSendCountry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countryinfo');
    }
};
