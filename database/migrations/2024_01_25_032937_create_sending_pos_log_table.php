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
        Schema::create('sending_pos_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_pos')->nullable(); 
            $table->text('mgisession')->nullable(); 
            $table->string('negara_penerima')->nullable(); 
            $table->integer('bsu')->nullable();  
            $table->string('valid_indicator')->nullable();  
            $table->string('account_id')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sending_pos_log');
    }
};
