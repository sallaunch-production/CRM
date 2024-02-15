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
        Schema::create('service_pos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('agent_id ')->nullable(); 
            $table->string('service')->nullable(); 
            $table->string('opt_display')->nullable(); 
            $table->string('currency')->nullable(); 
            $table->string('id_pos')->nullable(); 
            $table->datetime('tanggal')->nullable(); 
            $table->string('countryCode')->nullable(); 
            $table->string('countryName')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_pos');
    }
};
