<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * First-time (new) customer lookup across POS / Branch / Direct channels.
 * Keeps each source's "first transaction in period" logic isolated so listings stay scalable.
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
        $before = $startDate.' 23:59:59';

        $phoneSelect = $phone
            ? DB::raw("MIN(t1.{$phone}) AS phone")
            : DB::raw('NULL AS phone');

        $rows = DB::table("{$table} as t1")
            ->select(
                DB::raw('MIN(t1.no) AS no'),
                "t1.{$name} as customer_name",
                DB::raw('MIN(t1.no_referensi) AS no_referensi'),
                DB::raw('MIN(t1.tanggal) AS tanggal'),
                DB::raw('MIN(t1.id_pos) AS id_pos'),
                DB::raw('MIN(t1.jenis_kelamin) AS jenis_kelamin'),
                DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
                DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
                DB::raw("MIN(t1.{$place}) AS place_of_birth"),
                $phoneSelect
            )
            ->leftJoin("{$table} as t2", function ($join) use ($name, $before) {
                $join->on("t1.{$name}", '=', "t2.{$name}")
                    ->where("t2.tanggal", '<=', $before);
            })
            ->where("t1.tanggal", '>=', $from)
            ->where("t1.tanggal", '<=', $to)
            ->whereNull("t2.{$name}")
            ->groupBy("t1.{$name}")
            ->get();

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
