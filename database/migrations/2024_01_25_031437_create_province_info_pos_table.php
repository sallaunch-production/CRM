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
        Schema::create('province_info_pos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('countryCode')->nullable();
            $table->string('stateProvinceCode ')->nullable();
            $table->string('stateProvinceName')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('province_info_pos');
    }
};
