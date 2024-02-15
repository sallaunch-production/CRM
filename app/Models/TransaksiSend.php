<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiSend extends Model
{
    use HasFactory;
    protected $table = "transaksi_send";

    protected $fillable = [
        'no',
        'id_transaksi',
        'bsubanyak',
        'nama_pengirim',
        'nama_penerima',
        'negara_pengirim',
        'no_referensi',
        'identitas',
        'nomor_identitas',
        'tanggal_lahir',
        'alamat_penerima',
        'telepon_penerima',
        'kota_penerima',
        'pekerjaan',
        'tujuan_penggunaan_dana',
        'id_pos',
        'tanggal',
        'deltawork',
        'deltawork708',
        'provinsi',
        'mgsession',
        'sumber_dana',
        'tempat_lahir',
        'kode_pos',
        'jenis_kelamin',
        'amount',
        'pesan',
        'timestamp',
        'negara_penerima',
        'photoidcountry',
        'photoidstate',
        'arsema_validation',
        'status_cta',
        'receive_amount',
        'currency',
        'foto_id',
        'telpon_pengirim',
        'tanggal_transaksi',
        'sigue',
        'alamat_pengirim',
        'kota_pengirim',
        'provinsi_pengirim',
        'kode_pos_pengirim',
        'opt_display',
        'payout_currency',
        'receive_agent_abb',
        'receive_fee',
        'receive_tax',
        'total_receive_amount',
        'exchange_rate',
        'sending_fee',
        'kode_promosi',
        'consumer_text',
        'expected_date',
        'disclosuer_text',
        'dial',
        'pin',
        'receive_abb_name',
        'alamat_domisili_pengirim',
        'kota_domisili_pengirim',
        'kode_domisili_pengirim',
        'provinsi_domisili_pengirim',
        'negara_domisili_pengirim',
        'account_id',
    ];


}
