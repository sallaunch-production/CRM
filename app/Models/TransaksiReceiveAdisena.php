<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiReceiveAdisena extends Model
{
    use HasFactory;
    protected $table = "transaksi_receive_clients";

    protected $fillable = [
        'no', 'id_transaksi', 'bsubanyak', 'nama_pengirim', 'nama_penerima', 'negara_pengirim', 'no_referensi', 'identitas',
        'nomor_identitas', 'tanggal_lahir', 'alamat_penerima', 'telepon_penerima', 'kota_penerima', 'pekerjaan', 'tujuan_penggunaan_dana',
        'id_pos', 'tanggal', 'deltawork', 'deltawork708', 'provinsi', 'mgisession', 'sumber_dana', 'tempat_lahir', 'kode_pos', 'jenis_kelamin',
        'amount', 'pesan', 'timestamp', 'negara_penerima', 'photoidcountry', 'photoidstate', 'arsema_validation', 'status_cta',
        'bank', 'bankaccount', 'tanggal_transaksi', 'kewarganegaraan', 'kota_kelahiran', 'kode_pos_penerima', 'hubungan',
        'negara_lahir', 'receiverIntendedUseOfMGIServices', 'alamat_domisili_penerima', 'kota_domisili_penerima', 'kode_pos_domisili_penerima',
        'provinsi_domisili_penerima', 'negara_domisili_penerima', 'corporate', 'created_at', 'updated_at', 'maker', 'approver', 'agent_id'
    ];
    
    

}
