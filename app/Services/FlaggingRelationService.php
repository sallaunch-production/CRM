<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FlaggingRelationService
{
    public const THRESHOLD = 5;

    /**
     * Parse HTML date inputs start_date / end_date (Y-m-d).
     * End date must be >= start date.
     *
     * @return array{dari: string, sampai: string}
     */
    public function parseStartEndDates(string $startDate, string $endDate): array
    {
        $startDate = trim($startDate);
        $endDate = trim($endDate);

        if ($startDate === '' || $endDate === '') {
            throw new InvalidArgumentException('Start date dan end date wajib diisi.');
        }

        try {
            $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $endDate)->startOfDay();
        } catch (\Throwable $e) {
            throw new InvalidArgumentException('Format tanggal tidak valid.');
        }

        if ($end->lt($start)) {
            throw new InvalidArgumentException('End date tidak boleh lebih kecil dari start date.');
        }

        return [
            'dari' => $start->toDateString(),
            'sampai' => $end->toDateString(),
        ];
    }

    /**
     * @deprecated Prefer parseStartEndDates()
     * Parse daterangepicker value "MM/DD/YYYY - MM/DD/YYYY".
     *
     * @return array{dari: string, sampai: string}
     */
    public function parseReservationRange(string $reservation): array
    {
        $reservation = trim($reservation);

        if (! preg_match(
            '#^(\d{2})/(\d{2})/(\d{4})\s*-\s*(\d{2})/(\d{2})/(\d{4})$#',
            $reservation,
            $matches
        )) {
            throw new InvalidArgumentException('Format periode tidak valid.');
        }

        $dari = sprintf('%s-%s-%s', $matches[3], $matches[1], $matches[2]);
        $sampai = sprintf('%s-%s-%s', $matches[6], $matches[4], $matches[5]);

        return $this->parseStartEndDates($dari, $sampai);
    }

    /**
     * One pengirim (nama saja) → many penerima.
     */
    public function oneToManySummary(string $dari, string $sampai, int $threshold = self::THRESHOLD): Collection
    {
        return $this->baseQuery($dari, $sampai)
            ->select(
                'nama_pengirim',
                DB::raw('COUNT(DISTINCT nama_penerima) as perbuatan'),
                DB::raw("SUM(CAST(REPLACE(COALESCE(bsubanyak, '0'), ',', '') AS FLOAT)) as total_bsu")
            )
            ->whereNotNull('nama_pengirim')
            ->whereRaw("TRIM(nama_pengirim) <> ''")
            ->groupBy('nama_pengirim')
            ->havingRaw('COUNT(DISTINCT nama_penerima) > ?', [$threshold])
            ->orderByDesc('perbuatan')
            ->get();
    }

    /**
     * Many pengirim → one penerima (keyed by nama_penerima only; no NIK penerima).
     */
    public function manyToOneSummary(string $dari, string $sampai, int $threshold = self::THRESHOLD): Collection
    {
        return $this->baseQuery($dari, $sampai)
            ->select(
                'nama_penerima',
                DB::raw('COUNT(DISTINCT nama_pengirim) as perbuatan'),
                DB::raw('COUNT(DISTINCT nomor_identitas) as jumlah_nik_pengirim'),
                DB::raw("SUM(CAST(REPLACE(COALESCE(bsubanyak, '0'), ',', '') AS FLOAT)) as total_bsu")
            )
            ->whereNotNull('nama_penerima')
            ->whereRaw("TRIM(nama_penerima) <> ''")
            ->groupBy('nama_penerima')
            ->havingRaw('COUNT(DISTINCT nama_pengirim) > ?', [$threshold])
            ->orderByDesc('perbuatan')
            ->get();
    }

    public function oneToManyDetail(
        string $dari,
        string $sampai,
        string $namaPengirim
    ): Collection {
        return $this->baseQuery($dari, $sampai)
            ->where('nama_pengirim', $namaPengirim)
            ->orderByDesc('tanggal')
            ->get();
    }

    public function manyToOneDetail(string $dari, string $sampai, string $namaPenerima): Collection
    {
        return $this->baseQuery($dari, $sampai)
            ->where('nama_penerima', $namaPenerima)
            ->orderByDesc('tanggal')
            ->get();
    }

    protected function baseQuery(string $dari, string $sampai)
    {
        return DB::table('transaksi_valid')
            ->where('deltawork', 'ya')
            ->where('id_transaksi', '<>', '')
            ->where('tanggal', '>=', $dari.' 00:00:00')
            ->where('tanggal', '<=', $sampai.' 23:59:59');
    }
}
