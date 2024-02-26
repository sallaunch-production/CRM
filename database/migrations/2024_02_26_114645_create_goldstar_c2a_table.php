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
        Schema::create('goldstar_c2a', function (Blueprint $table) {
            $table->increments('id');
            $table->text('ref_number')->nullable();
            $table->string('sourcecountry')->nullable();
            $table->string('sendername')->nullable();
            $table->string('targetcurrency')->nullable();
            $table->string('targetamount')->nullable();
            $table->string('sourcecurrency')->nullable();
            $table->string('feecurrency')->nullable();
            $table->string('feeamount')->nullable();
            $table->string('feecountry')->nullable();
            $table->string('bankcode')->nullable();
            $table->string('bankname')->nullable();
            $table->text('bankaccount')->nullable();
            $table->text('nama_penerima')->nullable();
            $table->text('country')->nullable();
            $table->text('remarks')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->string('status')->nullable();
            $table->string('user')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('user_update')->nullable();
            $table->integer('BATCH')->nullable();
            $table->datetime('tanggal_upload')->nullable();
            $table->string('proses')->nullable();
            $table->enum('pending',['Y','N'])->nullable();
            $table->integer('batch_upload')->nullable();
            $table->string('bank_holder_account_name')->nullable();
            $table->string('reference_name')->nullable();
            $table->text('note_gs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goldstar_c2a');
    }
};
