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
        Schema::create('agent_id', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_pos')->nullable();
            $table->string('agent_id')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama')->nullable();
            $table->string('legacy')->nullable();
            $table->string('pos')->nullable();
            $table->string('pos_password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_id');
    }
};
