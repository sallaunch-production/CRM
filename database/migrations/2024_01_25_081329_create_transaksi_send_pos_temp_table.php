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
        Schema::create('transaksi_send_pos_temp', function (Blueprint $table) {
            $table->increments('no');
            $table->string('id_transaksi')->nullable(); 
            $table->string('bsubanyak')->nullable(); 
            $table->string('nama_pengirim')->nullable(); 
            $table->string('nama_penerima')->nullable(); 
            $table->string('negara_pengirim')->nullable(); 
            $table->string('no_referensi')->nullable(); 
            $table->string('identitas')->nullable(); 
            $table->string('nomor_identitas')->nullable(); 
            $table->string('tanggal_lahir')->nullable(); 
            $table->string('alamat_penerima')->nullable(); 
            $table->string('telepon_penerima')->nullable(); 
            $table->string('kota_penerima')->nullable(); 
            $table->string('pekerjaan')->nullable(); 
            $table->string('tujuan_penggunaan_dana')->nullable(); 
            $table->string('id_pos')->nullable(); 
            $table->datetime('tanggal')->nullable(); 
            $table->string('deltawork')->nullable(); 
            $table->string('deltawork708')->nullable(); 
            $table->string('provinsi')->nullable(); 
            $table->string('mgisession')->nullable(); 
            $table->text('sumber_dana')->nullable(); 
            $table->string('tempat_lahir')->nullable(); 
            $table->string('kode_pos')->nullable(); 
            $table->string('jenis_kelamin')->nullable(); 
            $table->string('amount')->nullable(); 
            $table->text('pesan')->nullable(); 
            $table->string('timestamp')->nullable(); 
            $table->string('negara_penerima')->nullable(); 
            $table->string('photoidcountry')->nullable(); 
            $table->string('photoidstate')->nullable(); 
            $table->string('arsema_validation')->nullable(); 
            $table->string('status_cta')->nullable(); 
            $table->string('receive_amount')->nullable(); 
            $table->string('currency')->nullable(); 
            $table->string('foto_id')->nullable(); 
            $table->string('telpon_pengirim')->nullable(); 
            $table->string('tanggal_transaksi')->nullable(); 
            $table->string('sigue')->nullable(); 
            $table->text('alamat_pengirim')->nullable(); 
            $table->string('kota_pengirim')->nullable(); 
            $table->string('provinsi_pengirim')->nullable(); 
            $table->string('kode_pos_pengirim')->nullable(); 
            $table->string('opt_display')->nullable(); 
            $table->string('payout_currency')->nullable(); 
            $table->string('receive_agent_abb')->nullable(); 
            $table->string('receive_fee')->nullable(); 
            $table->string('receive_tax')->nullable(); 
            $table->string('total_receive_amount')->nullable(); 
            $table->string('exchange_rate')->nullable(); 
            $table->string('sending_fee')->nullable(); 
            $table->string('kode_promosi')->nullable(); 
            $table->text('consumer_text')->nullable(); 
            $table->string('expected_date')->nullable(); 
            $table->text('disclosure_text')->nullable(); 
            $table->string('dial')->nullable(); 
            $table->string('pin')->nullable(); 
            $table->string('receive_abb_name')->nullable(); 
            $table->string('alamat_domisili_pengirim')->nullable(); 
            $table->string('kota_domisili_pengirim')->nullable(); 
            $table->string('kode_pos_domisili_pengirim')->nullable(); 
            $table->string('provinsi_domisili_pengirim')->nullable(); 
            $table->string('negara_domisili_pengirim')->nullable(); 
            $table->string('account_id')->nullable(); 
            $table->string('total_collect_amount')->nullable(); 
            $table->string('kota_lahir_pengirim')->nullable(); 
            $table->string('hubungan_pengirim')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_send_pos_temp');
    }
};
