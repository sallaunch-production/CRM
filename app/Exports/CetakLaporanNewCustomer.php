<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CetakLaporanNewCustomer implements FromView
{
    protected $tanggal_dari;
    protected $tanggal_sampai;

    public function __construct($tanggal_dari, $tanggal_sampai)
    {
        $this->tanggal_dari = $tanggal_dari;
        $this->tanggal_sampai = $tanggal_sampai;
    }

    public function view(): View
    {
        $tglDari = $this->tanggal_dari;
        $tglSampai = $this->tanggal_sampai;

        // === NEW CUSTOMER RECEIVE (gabungan 3 tabel) ===
        $q1 = DB::table('transaksi_valid as t1')
            ->select(
                't1.nama_penerima',
                't1.kota_kelahiran',
                't1.tanggal_lahir',
                't1.alamat_penerima',
                't1.nomor_identitas',
                't1.identitas',
                't1.no_referensi'
            )
            ->whereBetween('t1.tanggal', [$tglDari . ' 00:00:00', $tglSampai . ' 23:59:59'])
            ->whereNotExists(function ($query) use ($tglDari) {
                $query->select(DB::raw(1))
                    ->from('transaksi_valid as t2')
                    ->whereColumn('t2.nama_penerima', 't1.nama_penerima')
                    ->where('t2.tanggal', '<', $tglDari . ' 00:00:00');
            });

        $q2 = DB::table('transaksi_receive as t1')
            ->select(
                't1.nama_penerima',
                't1.kota_kelahiran',
                't1.tanggal_lahir',
                't1.alamat_penerima',
                't1.nomor_identitas',
                't1.identitas',
                't1.no_referensi'
            )
            ->whereBetween('t1.tanggal', [$tglDari . ' 00:00:00', $tglSampai . ' 23:59:59'])
            ->whereNotExists(function ($query) use ($tglDari) {
                $query->select(DB::raw(1))
                    ->from('transaksi_receive as t2')
                    ->whereColumn('t2.nama_penerima', 't1.nama_penerima')
                    ->where('t2.tanggal', '<', $tglDari . ' 00:00:00');
            });

        $q3 = DB::table('transaksi_receive_clients as t1')
            ->select(
                't1.nama_penerima',
                't1.kota_kelahiran',
                't1.tanggal_lahir',
                't1.alamat_penerima',
                't1.nomor_identitas',
                DB::raw("'' as identitas"),
                't1.no_referensi'
            )
            ->whereBetween('t1.tanggal', [$tglDari . ' 00:00:00', $tglSampai . ' 23:59:59'])
            ->whereNotExists(function ($query) use ($tglDari) {
                $query->select(DB::raw(1))
                    ->from('transaksi_receive_clients as t2')
                    ->whereColumn('t2.nama_penerima', 't1.nama_penerima')
                    ->where('t2.tanggal', '<', $tglDari . ' 00:00:00');
            });

        $unionReceive = $q1->union($q2)->union($q3)->get();

        // === NEW CUSTOMER SEND ===
       $querSend = DB::table('transaksi_send_pos as t1')
    ->select(
        DB::raw('DISTINCT ON (t1.nomor_identitas) t1.nomor_identitas'),
        't1.nama_pengirim',
        't1.kota_lahir_pengirim',
        't1.tanggal_lahir',
        't1.alamat_pengirim',
        't1.no_referensi'
    )
    ->whereBetween('t1.tanggal', [$tglDari . ' 00:00:00', $tglSampai . ' 23:59:59'])
    ->whereNotExists(function ($query) use ($tglDari) {
        $query->select(DB::raw(1))
            ->from('transaksi_send_pos as t2')
            ->whereColumn('t2.nomor_identitas', 't1.nomor_identitas')
            ->where('t2.tanggal', '<', $tglDari . ' 00:00:00');
    })
    ->orderBy('t1.nomor_identitas')
    ->orderBy('t1.tanggal') // biar ambil kiriman pertama dari identitas tsb
    ->get();





        return view('excel.new_customer', [
            'querReceive' => $unionReceive,
            'querSend' => $querSend,
            'tanggal_dari' => $tglDari,
            'tanggal_sampai' => $tglSampai
        ]);
    }
}
