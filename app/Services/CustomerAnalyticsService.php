<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CustomerAnalyticsService
{
    public function newSendCustomers(
        CarbonInterface $startDate,
        CarbonInterface $endDateExclusive
    ): Builder {
        return DB::table('transaksi_send_pos as transaction')
            ->select(
                'transaction.no',
                'transaction.id_transaksi',
                'transaction.nama_pengirim',
                'transaction.nomor_identitas',
                'transaction.telpon_pengirim as nomor_telepon',
                'transaction.negara_penerima as negara_tujuan',
                'transaction.amount as jumlah_transaksi',
                'transaction.tanggal as tanggal_transaksi',
                'transaction.no_referensi',
                'transaction.id_pos as nopend'
            )
            ->where('transaction.tanggal', '>=', $startDate)
            ->where('transaction.tanggal', '<', $endDateExclusive)
            ->whereNotNull('transaction.nomor_identitas')
            ->whereRaw("TRIM(transaction.nomor_identitas) <> ''")
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('transaksi_send_pos as previous')
                    ->whereRaw(
                        'TRIM(previous.nomor_identitas) = TRIM(transaction.nomor_identitas)'
                    )
                    ->where(function ($query) {
                        $query->whereColumn('previous.tanggal', '<', 'transaction.tanggal')
                            ->orWhere(function ($query) {
                                $query->whereColumn('previous.tanggal', 'transaction.tanggal')
                                    ->whereColumn('previous.no', '<', 'transaction.no');
                            });
                    });
            })
            ->orderBy('transaction.tanggal')
            ->orderBy('transaction.no');
    }

    public function newReceiveCustomers(
        CarbonInterface $startDate,
        CarbonInterface $endDateExclusive
    ): Builder {
        return DB::table('transaksi_valid as transaction')
            ->select(
                'transaction.no',
                'transaction.id_transaksi',
                'transaction.nama_penerima',
                'transaction.nomor_identitas',
                'transaction.telepon_penerima as nomor_telepon',
                'transaction.negara_pengirim',
                'transaction.amount as jumlah_transaksi',
                'transaction.tanggal as tanggal_transaksi',
                'transaction.no_referensi',
                'transaction.id_pos as nopend'
            )
            ->where('transaction.tanggal', '>=', $startDate)
            ->where('transaction.tanggal', '<', $endDateExclusive)
            ->whereNotNull('transaction.nomor_identitas')
            ->whereRaw("TRIM(transaction.nomor_identitas) <> ''")
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('transaksi_valid as previous')
                    ->whereRaw(
                        'TRIM(previous.nomor_identitas) = TRIM(transaction.nomor_identitas)'
                    )
                    ->where(function ($query) {
                        $query->whereColumn('previous.tanggal', '<', 'transaction.tanggal')
                            ->orWhere(function ($query) {
                                $query->whereColumn('previous.tanggal', 'transaction.tanggal')
                                    ->whereColumn('previous.no', '<', 'transaction.no');
                            });
                    });
            })
            ->orderBy('transaction.tanggal')
            ->orderBy('transaction.no');
    }

    public function customerTransactionSummary(
        CarbonInterface $startDate,
        CarbonInterface $endDateExclusive
    ): Builder {
        return DB::table('transaksi_send_pos as transaction')
            ->select(
                DB::raw('MIN(transaction.nama_pengirim) AS nama_pengirim'),
                DB::raw('TRIM(transaction.nomor_identitas) AS nomor_identitas'),
                DB::raw('COUNT(*) AS jumlah_transaksi')
            )
            ->where('transaction.tanggal', '>=', $startDate)
            ->where('transaction.tanggal', '<', $endDateExclusive)
            ->whereNotNull('transaction.nomor_identitas')
            ->whereRaw("TRIM(transaction.nomor_identitas) <> ''")
            ->groupByRaw('TRIM(transaction.nomor_identitas)')
            ->orderByDesc('jumlah_transaksi')
            ->orderBy('nama_pengirim');
    }

    public function customerTransactions(
        string $identityNumber,
        CarbonInterface $startDate,
        CarbonInterface $endDateExclusive
    ): Builder {
        return DB::table('transaksi_send_pos as transaction')
            ->select(
                'transaction.no',
                'transaction.no_referensi',
                'transaction.tanggal',
                'transaction.id_pos',
                'transaction.nama_pengirim',
                'transaction.nomor_identitas',
                'transaction.jenis_kelamin',
                'transaction.kota_lahir_pengirim',
                'transaction.tanggal_lahir',
                'transaction.telpon_pengirim',
                'transaction.nama_penerima',
                'transaction.negara_penerima',
                'transaction.amount'
            )
            ->whereRaw('TRIM(transaction.nomor_identitas) = ?', [trim($identityNumber)])
            ->where('transaction.tanggal', '>=', $startDate)
            ->where('transaction.tanggal', '<', $endDateExclusive)
            ->orderByDesc('transaction.tanggal');
    }
}
