<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GenerateMonthlyCustomerReport implements FromView, ShouldAutoSize
{
    protected string $tanggalDari;
    protected string $tanggalSampai;

    public function __construct(string $tanggalDari, ?string $tanggalSampai = null)
    {
        $this->tanggalDari = $tanggalDari;
        $this->tanggalSampai = $tanggalSampai ?? $tanggalDari;
    }

    public function view(): View
    {
        $tanggalBesok = Carbon::parse($this->tanggalSampai)
            ->addDay()
            ->format('Y-m-d');

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
            ->where('t.tanggal', '>=', $this->tanggalDari . ' 00:00:00')
            ->where('t.tanggal', '<', $tanggalBesok . ' 00:00:00')
            ->whereNotNull('t.nomor_identitas')
            ->where('t.nomor_identitas', '<>', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('transaksi_send_pos as old')
                    ->whereColumn('old.nomor_identitas', 't.nomor_identitas')
                    ->where(function ($q) {
                        $q->whereColumn('old.tanggal', '<', 't.tanggal')
                            ->orWhere(function ($q2) {
                                $q2->whereColumn('old.tanggal', 't.tanggal')
                                    ->whereColumn('old.id_transaksi', '<', 't.id_transaksi');
                            });
                    });
            })
            ->orderBy('t.tanggal', 'asc')
            ->orderBy('t.id_transaksi', 'asc')
            ->get();

        $data->transform(function ($row) {
            $row->jumlah_transaksi = 'Rp ' . number_format((float) $row->jumlah_transaksi, 0, ',', '.');

            return $row;
        });

        return view('excel.new_customer_monthly_excel', [
            'data' => $data,
            'tanggalDari' => $this->tanggalDari,
            'tanggalSampai' => $this->tanggalSampai,
        ]);
    }
}
