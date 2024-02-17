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
        Schema::create('amend', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_referensi')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('last_name2')->nullable();
            $table->datetime('created_date')->nullable();
            $table->string('operator')->nullable();
            $table->string('checker')->nullable();
            $table->string('approval')->nullable();
            $table->int('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amend');
    }
};
