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
        Schema::create('negara_konverter', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bi')->nullable();
            $table->string('ppatk')->nullable();
            $table->string('nama')->nullable();
            $table->string('kodeppatk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negara_konverter');
    }
};
