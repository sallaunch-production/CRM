<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * First-time (new) customer lookup across POS / Branch / Direct channels.
 * Uniqueness is keyed by ID number (nomor_identitas) within each source table.
 */
class NewCustomerQueryService
{
    public const CHANNELS = ['all', 'pos', 'branch', 'direct'];

    public const DIRECTIONS = ['all', 'receive', 'send'];

    /**
     * @return list<array{
     *   channel:string,
     *   direction:string,
     *   table:string,
     *   name_column:string,
     *   place_column:string,
     *   phone_column:?string,
     *   detail_compliance:string,
     *   detail_operasional:string,
     *   channel_label:string,
     *   direction_label:string
     * }>
     */
    public function sources(?string $channel = 'all', ?string $direction = 'all'): array
    {
        $channel = $this->normalizeChannel($channel);
        $direction = $this->normalizeDirection($direction);

        $all = [
            [
                'channel' => 'pos',
                'direction' => 'receive',
                'table' => 'transaksi_valid',
                'name_column' => 'nama_penerima',
                'place_column' => 'kota_kelahiran',
                'phone_column' => null,
                'detail_compliance' => 'transaksi_receive_pos.detail',
                'detail_operasional' => 'transaksi_receive_pos_operasional.detail',
                'channel_label' => 'POS',
                'direction_label' => 'Receive',
            ],
            [
                'channel' => 'pos',
                'direction' => 'send',
                'table' => 'transaksi_send_pos',
                'name_column' => 'nama_pengirim',
                'place_column' => 'kota_lahir_pengirim',
                'phone_column' => 'telpon_pengirim',
                'detail_compliance' => 'transaksi_send_pos.detail',
                'detail_operasional' => 'transaksi_send_pos_operasional.detail',
                'channel_label' => 'POS',
                'direction_label' => 'Send',
            ],
            [
                'channel' => 'branch',
                'direction' => 'receive',
                'table' => 'transaksi_receive_clients',
                'name_column' => 'nama_penerima',
                'place_column' => 'kota_kelahiran',
                'phone_column' => null,
                'detail_compliance' => 'transaction_receive_adisena_compliance.detail',
                'detail_operasional' => 'transaction_receive_adisena_operasional.detail',
                'channel_label' => 'Branch',
                'direction_label' => 'Receive',
            ],
            [
                'channel' => 'branch',
                'direction' => 'send',
                'table' => 'transaksi_send_clients',
                'name_column' => 'nama_pengirim',
                'place_column' => 'kota_lahir_pengirim',
                'phone_column' => 'telpon_pengirim',
                'detail_compliance' => 'transaction_send_adisena_compliance.detail',
                'detail_operasional' => 'transaction_send_adisena_operasional.detail',
                'channel_label' => 'Branch',
                'direction_label' => 'Send',
            ],
            [
                'channel' => 'direct',
                'direction' => 'receive',
                'table' => 'transaksi_receive',
                'name_column' => 'nama_penerima',
                'place_column' => 'kota_kelahiran',
                'phone_column' => null,
                'detail_compliance' => 'transaction_receive_direct_compliance.detail',
                'detail_operasional' => 'transaction_receive_direct_operasional.detail',
                'channel_label' => 'Direct',
                'direction_label' => 'Receive',
            ],
            [
                'channel' => 'direct',
                'direction' => 'send',
                'table' => 'transaksi_send',
                'name_column' => 'nama_pengirim',
                'place_column' => 'kota_lahir_pengirim',
                'phone_column' => 'telpon_pengirim',
                'detail_compliance' => 'transaction_send_direct_compliance.detail',
                'detail_operasional' => 'transaction_send_direct_operasional.detail',
                'channel_label' => 'Direct',
                'direction_label' => 'Send',
            ],
        ];

        return array_values(array_filter($all, function (array $source) use ($channel, $direction) {
            if ($channel !== 'all' && $source['channel'] !== $channel) {
                return false;
            }
            if ($direction !== 'all' && $source['direction'] !== $direction) {
                return false;
            }

            return true;
        }));
    }

    public function normalizeChannel(?string $channel): string
    {
        $channel = strtolower(trim((string) $channel));

        return in_array($channel, self::CHANNELS, true) ? $channel : 'all';
    }

    public function normalizeDirection(?string $direction): string
    {
        $direction = strtolower(trim((string) $direction));

        return in_array($direction, self::DIRECTIONS, true) ? $direction : 'all';
    }

    /**
     * @return Collection<int, object>
     */
    public function fetch(string $channel, string $direction, string $startDate, string $endDate): Collection
    {
        $rows = collect();

        foreach ($this->sources($channel, $direction) as $source) {
            $rows = $rows->merge($this->querySource($source, $startDate, $endDate));
        }

        return $rows
            ->sortBy(fn ($row) => mb_strtoupper((string) ($row->customer_name ?? '')))
            ->values();
    }

    /**
     * New customer = first transaction for this ID number (nomor_identitas)
     * in the selected source table falls inside [startDate, endDate]
     * (no prior tx with the same ID before start of period).
     *
     * @param  array{
     *   channel:string,
     *   direction:string,
     *   table:string,
     *   name_column:string,
     *   place_column:string,
     *   phone_column:?string,
     *   detail_compliance:string,
     *   detail_operasional:string,
     *   channel_label:string,
     *   direction_label:string
     * }  $source
     * @return Collection<int, object>
     */
    protected function querySource(array $source, string $startDate, string $endDate): Collection
    {
        $table = $source['table'];
        $name = $source['name_column'];
        $place = $source['place_column'];
        $phone = $source['phone_column'];
        $from = $startDate.' 00:00:00';
        $to = $endDate.' 23:59:59';

        // Columns are whitelist-controlled by $this->sources(); safe for interpolation.
        $phoneSelect = $phone ? "t.{$phone}" : 'NULL';

        $sql = <<<SQL
            SELECT DISTINCT ON (TRIM(CAST(t.nomor_identitas AS TEXT)))
                t.no,
                t.{$name} AS customer_name,
                t.no_referensi,
                t.tanggal,
                t.id_pos,
                t.jenis_kelamin,
                t.nomor_identitas,
                t.tanggal_lahir,
                t.{$place} AS place_of_birth,
                {$phoneSelect} AS phone
            FROM {$table} AS t
            WHERE t.tanggal >= ?
              AND t.tanggal <= ?
              AND t.nomor_identitas IS NOT NULL
              AND TRIM(CAST(t.nomor_identitas AS TEXT)) <> ''
              AND NOT EXISTS (
                  SELECT 1
                  FROM {$table} AS prior
                  WHERE TRIM(CAST(prior.nomor_identitas AS TEXT)) = TRIM(CAST(t.nomor_identitas AS TEXT))
                    AND prior.tanggal < ?
              )
            ORDER BY TRIM(CAST(t.nomor_identitas AS TEXT)) ASC, t.tanggal ASC, t.no ASC
        SQL;

        $rows = collect(DB::select($sql, [$from, $to, $from]));

        return $rows->map(function ($row) use ($source) {
            $row->channel = $source['channel'];
            $row->direction = $source['direction'];
            $row->channel_label = $source['channel_label'];
            $row->direction_label = $source['direction_label'];
            $row->detail_compliance = $source['detail_compliance'];
            $row->detail_operasional = $source['detail_operasional'];

            return $row;
        });
    }
}
