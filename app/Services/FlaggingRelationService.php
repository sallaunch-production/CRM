<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FlaggingRelationService
{
    public const THRESHOLD = 5;

    /** Default BSU threshold for NIK aggregation flagging (Rp). */
    public const DEFAULT_NOMINAL_THRESHOLD = 100000000;

    public const TABLE_SENDING = 'transaksi_send_pos';

    public const TABLE_RECEIVING = 'transaksi_valid';

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
     * Parse mandatory nominal threshold from the UI.
     * Accepts plain digits or thousand-separated values (e.g. 100000000 / 100.000.000 / 100,000,000).
     */
    public function parseNominalThreshold($raw): float
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            throw new InvalidArgumentException('Nominal ambang wajib diisi.');
        }

        $cleaned = preg_replace('/[^\d.]/', '', $raw);
        if ($cleaned === '' || ! is_numeric($cleaned)) {
            throw new InvalidArgumentException('Nominal ambang harus berupa angka.');
        }

        $value = (float) $cleaned;
        if ($value <= 0) {
            throw new InvalidArgumentException('Nominal ambang harus lebih dari 0.');
        }

        return $value;
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
     * One to Many (sending only):
     * 1 ID pengirim (nomor_identitas) → banyak nama penerima.
     */
    public function oneToManySummary(string $dari, string $sampai, int $threshold = self::THRESHOLD): Collection
    {
        return $this->sendingQuery($dari, $sampai)
            ->select(
                'nomor_identitas',
                DB::raw('MAX(nama_pengirim) as nama_pengirim'),
                DB::raw('COUNT(DISTINCT nama_penerima) as perbuatan'),
                DB::raw($this->sumAmountExpression('amount').' as total_bsu')
            )
            ->whereNotNull('nomor_identitas')
            ->whereRaw("TRIM(nomor_identitas) <> ''")
            ->whereNotNull('nama_penerima')
            ->whereRaw("TRIM(nama_penerima) <> ''")
            ->groupBy('nomor_identitas')
            ->havingRaw('COUNT(DISTINCT nama_penerima) > ?', [$threshold])
            ->orderByDesc('perbuatan')
            ->get();
    }

    /**
     * Many pengirim → one penerima (receiving / transaksi_valid).
     * Keyed by nama_penerima only; no NIK penerima.
     */
    public function manyToOneSummary(string $dari, string $sampai, int $threshold = self::THRESHOLD): Collection
    {
        return $this->receivingQuery($dari, $sampai)
            ->select(
                'nama_penerima',
                DB::raw('COUNT(DISTINCT nama_pengirim) as perbuatan'),
                DB::raw('COUNT(DISTINCT nomor_identitas) as jumlah_nik_pengirim'),
                DB::raw($this->sumAmountExpression('bsubanyak').' as total_bsu')
            )
            ->whereNotNull('nama_penerima')
            ->whereRaw("TRIM(nama_penerima) <> ''")
            ->groupBy('nama_penerima')
            ->havingRaw('COUNT(DISTINCT nama_pengirim) > ?', [$threshold])
            ->orderByDesc('perbuatan')
            ->get();
    }

    /**
     * NIK aggregation (receiving / transaksi_valid):
     * group by nomor_identitas penerima where total BSU in period > threshold.
     */
    public function nikAmountSummary(
        string $dari,
        string $sampai,
        float $threshold = self::DEFAULT_NOMINAL_THRESHOLD
    ): Collection {
        $sumExpr = $this->sumAmountExpression('bsubanyak');

        return $this->receivingQuery($dari, $sampai)
            ->select(
                'nomor_identitas',
                DB::raw('MAX(nama_penerima) as nama_penerima'),
                DB::raw('MAX(telepon_penerima) as telepon_penerima'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw($sumExpr.' as total_bsu')
            )
            ->whereNotNull('nomor_identitas')
            ->whereRaw("TRIM(nomor_identitas) <> ''")
            ->groupBy('nomor_identitas')
            ->havingRaw($sumExpr.' > ?', [$threshold])
            ->orderByDesc('total_bsu')
            ->get();
    }

    public function oneToManyDetail(
        string $dari,
        string $sampai,
        string $nomorIdentitas
    ): Collection {
        return $this->sendingQuery($dari, $sampai)
            ->where('nomor_identitas', $nomorIdentitas)
            ->orderByDesc('tanggal')
            ->get();
    }

    public function manyToOneDetail(string $dari, string $sampai, string $namaPenerima): Collection
    {
        return $this->receivingQuery($dari, $sampai)
            ->where('nama_penerima', $namaPenerima)
            ->orderByDesc('tanggal')
            ->get();
    }

    public function nikAmountDetail(string $dari, string $sampai, string $nomorIdentitas): Collection
    {
        return $this->receivingQuery($dari, $sampai)
            ->where('nomor_identitas', $nomorIdentitas)
            ->orderByDesc('tanggal')
            ->get();
    }

    /**
     * Sending transactions source (POS send).
     * Note: deltawork is mostly empty on send rows — do not require deltawork = 'ya'.
     */
    protected function sendingQuery(string $dari, string $sampai): Builder
    {
        return DB::table(self::TABLE_SENDING)
            ->whereNotNull('id_transaksi')
            ->where('id_transaksi', '<>', '')
            ->where('tanggal', '>=', $dari.' 00:00:00')
            ->where('tanggal', '<=', $sampai.' 23:59:59');
    }

    /**
     * Receiving / validated inbound source used by existing flagging categories.
     */
    protected function receivingQuery(string $dari, string $sampai): Builder
    {
        return DB::table(self::TABLE_RECEIVING)
            ->where('deltawork', 'ya')
            ->where('id_transaksi', '<>', '')
            ->where('tanggal', '>=', $dari.' 00:00:00')
            ->where('tanggal', '<=', $sampai.' 23:59:59');
    }

    /**
     * @deprecated Use receivingQuery() — kept for callers that still expect mixed base.
     */
    protected function baseQuery(string $dari, string $sampai): Builder
    {
        return $this->receivingQuery($dari, $sampai);
    }

    protected function sumAmountExpression(string $column): string
    {
        return "SUM(CAST(REPLACE(COALESCE({$column}, '0'), ',', '') AS FLOAT))";
    }
}
