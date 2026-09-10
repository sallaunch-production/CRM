<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class GenerateMonthlyCustomerPdf
{
    protected $tanggalDari;
    protected $tanggalSampai;

    public function __construct($tanggalDari, $tanggalSampai = null)
    {
        $this->tanggalDari = $tanggalDari;
        $this->tanggalSampai = $tanggalSampai ?? $tanggalDari;
    }

    // Ambil data & generate PDF, lalu return object PDF-nya
    // supaya di controller tinggal panggil ->download() atau ->stream()

    public function generate()
    {
        $tanggalDari = $this->tanggalDari;
        $tanggalSampai = $this->tanggalSampai;

        $tanggalBesok = Carbon::parse($tanggalSampai)->addDay()->format('Y-m-d');

        $data = DB::table('transaksi_send_pos as t')
            ->select(
                't.nama_pengirim as nama_pengirim',
                't.nomor_identitas as nomor_identitas',
                't.tanggal as tanggal_transaksi',
                't.no_referensi as no_referensi',
                't.id_pos as nopend',
                't.negara_penerima as negara_tujuan',
                't.amount as jumlah_transaksi',
                't.telpon_pengirim as nomor_telepon'
            )

            // Hanya transaksi pada bulan yang dipilih
            ->where('t.tanggal', '>=', $tanggalDari . ' 00:00:00')
            ->where('t.tanggal', '<', $tanggalBesok . ' 00:00:00')

            // Identitas harus ada
            ->whereNotNull('t.nomor_identitas')
            ->where('t.nomor_identitas', '<>', '')

            // Cari apakah customer sudah pernah transaksi sebelumnya
            // (dicek dari awal waktu, tidak dibatasi bulan yang dipilih)
            ->whereNotExists(function ($query) {

                $query->select(DB::raw(1))
                    ->from('transaksi_send_pos as old')

                    // CUSTOMER YANG SAMA
                    ->whereColumn('old.nomor_identitas', 't.nomor_identitas')

                    // Transaksi sebelumnya
                    ->where(function ($q) {

                        $q->whereColumn('old.tanggal', '<', 't.tanggal')

                            // Jika tanggal sama, gunakan id_transaksi
                            ->orWhere(function ($q2) {

                                $q2->whereColumn('old.tanggal', 't.tanggal')
                                    ->whereColumn('old.id_transaksi', '<', 't.id_transaksi');
                            });
                    });
            })

            ->orderBy('t.tanggal', 'asc')
            ->orderBy('t.id_transaksi', 'asc')
            ->get();

        // Format amount menjadi Rupiah
        $data->transform(function ($row) {
            $row->jumlah_transaksi = 'Rp ' . number_format($row->jumlah_transaksi, 0, ',', '.');
            return $row;
        });

        return Pdf::loadView('pdf.new_customer_monthly_pdf', [
            'data' => $data,
            'tanggalDari' => $tanggalDari,
            'tanggalSampai' => $tanggalSampai,
        ])->setPaper('a4', 'landscape');
    }


    public function getFilename(): string
    {
        $namaBulan = Carbon::parse($this->tanggalDari)
            ->locale('id')
            ->translatedFormat('F Y');

        return 'Monthly New Customer ' . $namaBulan . '.pdf';
    }

}