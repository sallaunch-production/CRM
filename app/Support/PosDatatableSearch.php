<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

class PosDatatableSearch
{
    /**
     * Single DataTables search input filters these columns:
     * NOPEND, ID, NOREF, NAMA PENGIRIM, NAMA PENERIMA, NOMINAL/BSU
     *
     * @param  Builder  $query
     * @param  string|null  $searchTerm  DataTables search.value
     * @param  string  $amountColumn  receive: bsubanyak, send: amount
     */
    public static function apply(Builder $query, ?string $searchTerm, string $amountColumn): Builder
    {
        $searchTerm = trim((string) $searchTerm);

        if ($searchTerm === '') {
            return $query;
        }

        if (! in_array($amountColumn, ['amount', 'bsubanyak'], true)) {
            throw new \InvalidArgumentException('Unsupported amount column for POS search.');
        }

        $like = '%' . $searchTerm . '%';
        $normalizedAmount = preg_replace('/[^\d]/', '', $searchTerm) ?? '';

        return $query->where(function (Builder $q) use ($like, $normalizedAmount, $amountColumn) {
            $q->whereRaw('CAST(id_pos AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(id_transaksi AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(no_referensi AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(nama_pengirim AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(nama_penerima AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw("CAST({$amountColumn} AS TEXT) ILIKE ?", [$like]);

            if ($normalizedAmount !== '') {
                $q->orWhereRaw(
                    "REPLACE(REPLACE(REPLACE(CAST({$amountColumn} AS TEXT), ',', ''), '.', ''), ' ', '') ILIKE ?",
                    ['%' . $normalizedAmount . '%']
                );
            }
        });
    }
}
