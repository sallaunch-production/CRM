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

    /** Default BSU threshold for High Nominal flagging (Rp). */
    public const DEFAULT_NOMINAL_THRESHOLD = 100000000;

    /** @deprecated Kept for BC; prefer channelSources(). */
    public const TABLE_SENDING = 'transaksi_send_pos';

    /** @deprecated Kept for BC; prefer channelSources(). */
    public const TABLE_RECEIVING = 'transaksi_valid';

    /**
     * Pos / Branch / Direct × Send / Receive.
     * Only transaksi_valid (Pos receive) requires deltawork = 'ya'.
     *
     * @return list<array{table: string, kanal: string, arah: string, require_deltawork: bool}>
     */
    protected function channelSources(): array
    {
        return [
            ['table' => 'transaksi_valid', 'kanal' => 'pos', 'arah' => 'receive', 'require_deltawork' => true],
            ['table' => 'transaksi_send_pos', 'kanal' => 'pos', 'arah' => 'send', 'require_deltawork' => false],
            ['table' => 'transaksi_receive', 'kanal' => 'direct', 'arah' => 'receive', 'require_deltawork' => false],
            ['table' => 'transaksi_send', 'kanal' => 'direct', 'arah' => 'send', 'require_deltawork' => false],
            ['table' => 'transaksi_receive_clients', 'kanal' => 'branch', 'arah' => 'receive', 'require_deltawork' => false],
            ['table' => 'transaksi_send_clients', 'kanal' => 'branch', 'arah' => 'send', 'require_deltawork' => false],
        ];
    }

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
     * One to Many: 1 nomor_identitas → banyak nama_penerima
     * across Pos/Branch/Direct send+receive.
     */
    public function oneToManySummary(string $dari, string $sampai, int $threshold = self::THRESHOLD): Collection
    {
        return $this->unifiedQuery($dari, $sampai)
            ->select(
                'nomor_identitas',
                DB::raw('MAX(nama_pengirim) as nama_pengirim'),
                DB::raw('COUNT(DISTINCT nama_penerima) as perbuatan'),
                DB::raw('SUM(amount_num) as total_bsu')
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
     * Many to One: banyak pengirim → 1 penerima (by nama_penerima)
     * across Pos/Branch/Direct send+receive.
     */
    public function manyToOneSummary(string $dari, string $sampai, int $threshold = self::THRESHOLD): Collection
    {
        return $this->unifiedQuery($dari, $sampai)
            ->select(
                'nama_penerima',
                DB::raw('COUNT(DISTINCT nama_pengirim) as perbuatan'),
                DB::raw('COUNT(DISTINCT nomor_identitas) as jumlah_nik_pengirim'),
                DB::raw('SUM(amount_num) as total_bsu')
            )
            ->whereNotNull('nama_penerima')
            ->whereRaw("TRIM(nama_penerima) <> ''")
            ->groupBy('nama_penerima')
            ->havingRaw('COUNT(DISTINCT nama_pengirim) > ?', [$threshold])
            ->orderByDesc('perbuatan')
            ->get();
    }

    /**
     * High Nominal: group by nomor_identitas where total BSU in period > threshold
     * across Pos/Branch/Direct send+receive.
     */
    public function nikAmountSummary(
        string $dari,
        string $sampai,
        float $threshold = self::DEFAULT_NOMINAL_THRESHOLD
    ): Collection {
        return $this->unifiedQuery($dari, $sampai)
            ->select(
                'nomor_identitas',
                DB::raw('MAX(nama_penerima) as nama_penerima'),
                DB::raw('MAX(telepon_penerima) as telepon_penerima'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(amount_num) as total_bsu')
            )
            ->whereNotNull('nomor_identitas')
            ->whereRaw("TRIM(nomor_identitas) <> ''")
            ->groupBy('nomor_identitas')
            ->havingRaw('SUM(amount_num) > ?', [$threshold])
            ->orderByDesc('total_bsu')
            ->get();
    }

    public function oneToManyDetail(
        string $dari,
        string $sampai,
        string $nomorIdentitas
    ): Collection {
        return $this->unifiedQuery($dari, $sampai)
            ->where('nomor_identitas', $nomorIdentitas)
            ->orderByDesc('tanggal')
            ->get();
    }

    public function manyToOneDetail(string $dari, string $sampai, string $namaPenerima): Collection
    {
        return $this->unifiedQuery($dari, $sampai)
            ->where('nama_penerima', $namaPenerima)
            ->orderByDesc('tanggal')
            ->get();
    }

    public function nikAmountDetail(string $dari, string $sampai, string $nomorIdentitas): Collection
    {
        return $this->unifiedQuery($dari, $sampai)
            ->where('nomor_identitas', $nomorIdentitas)
            ->orderByDesc('tanggal')
            ->get();
    }

    /**
     * Union of all channel tables with normalized amount + detail-friendly aliases.
     */
    protected function unifiedQuery(string $dari, string $sampai): Builder
    {
        $parts = [];
        foreach ($this->channelSources() as $source) {
            $parts[] = $this->channelSelect($source, $dari, $sampai);
        }

        $union = array_shift($parts);
        foreach ($parts as $part) {
            $union->unionAll($part);
        }

        return DB::query()->fromSub($union, 'flag_tx');
    }

    /**
     * @param  array{table: string, kanal: string, arah: string, require_deltawork: bool}  $source
     */
    protected function channelSelect(array $source, string $dari, string $sampai): Builder
    {
        $amountNum = $this->amountNumExpression();

        $query = DB::table($source['table'])
            ->selectRaw("
                id_transaksi,
                no_referensi,
                nomor_identitas,
                identitas,
                nama_pengirim,
                nama_penerima,
                telepon_penerima,
                tanggal_lahir,
                alamat_penerima,
                kota_penerima,
                pekerjaan,
                id_pos,
                tanggal,
                negara_pengirim,
                negara_penerima,
                {$amountNum} as amount_num,
                COALESCE(NULLIF(TRIM(COALESCE(bsubanyak::text, '')), ''), NULLIF(TRIM(COALESCE(amount::text, '')), '')) as bsubanyak,
                COALESCE(NULLIF(TRIM(COALESCE(amount::text, '')), ''), NULLIF(TRIM(COALESCE(bsubanyak::text, '')), '')) as amount,
                ? as kanal,
                ? as arah
            ", [$source['kanal'], $source['arah']])
            ->whereNotNull('id_transaksi')
            ->where('id_transaksi', '<>', '')
            ->where('tanggal', '>=', $dari.' 00:00:00')
            ->where('tanggal', '<=', $sampai.' 23:59:59');

        if (! empty($source['require_deltawork'])) {
            $query->where('deltawork', 'ya');
        }

        return $query;
    }

    /**
     * Prefer bsubanyak (receive Pos), fallback to amount (send / branch / direct).
     */
    protected function amountNumExpression(): string
    {
        return "CAST(
            NULLIF(
                REPLACE(
                    COALESCE(
                        NULLIF(TRIM(COALESCE(bsubanyak::text, '')), ''),
                        NULLIF(TRIM(COALESCE(amount::text, '')), ''),
                        '0'
                    ),
                    ',',
                    ''
                ),
                ''
            ) AS FLOAT
        )";
    }

    /**
     * @deprecated Use unifiedQuery() — kept so older callers stay safe.
     */
    protected function sendingQuery(string $dari, string $sampai): Builder
    {
        return $this->unifiedQuery($dari, $sampai)->where('arah', 'send');
    }

    /**
     * @deprecated Use unifiedQuery() — kept so older callers stay safe.
     */
    protected function receivingQuery(string $dari, string $sampai): Builder
    {
        return $this->unifiedQuery($dari, $sampai)->where('arah', 'receive');
    }

    /**
     * @deprecated Use unifiedQuery().
     */
    protected function baseQuery(string $dari, string $sampai): Builder
    {
        return $this->unifiedQuery($dari, $sampai);
    }

    protected function sumAmountExpression(string $column): string
    {
        return "SUM(CAST(REPLACE(COALESCE({$column}, '0'), ',', '') AS FLOAT))";
    }
}
