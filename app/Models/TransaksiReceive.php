<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TransaksiReceive extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $table = "transaksi_receive";

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
        'bank',
        'bankaccount',
        'tanggal_transaksi',
        'kewarganegraan',
        'kota_kelahiran',
        'kode_pos_penerima',
        'hubungan',
        'negara_lahir',
        'alamat_domisili_penerima',
        'kota_domisili_penerima',
        'kode_pos_domisili',
        'provinsi_domisili_penerima',
        'negara_domisili_penerima',
        'receiverIntendedUseOfMGIServices',
    ];


}
