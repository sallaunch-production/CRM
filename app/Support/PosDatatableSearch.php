<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

class PosDatatableSearch
{
    /**
     * Single DataTables search input filters:
     * NOPEND, ID, NOREF, NAMA PENGIRIM/PENERIMA, NOMINAL/BSU,
     * NOMOR IDENTITAS, TELEPON (+ telpon_pengirim for send),
     * ALAMAT (listing-hidden fields remain searchable).
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

        $like = '%'.$searchTerm.'%';
        $digitsOnly = preg_replace('/\D+/', '', $searchTerm) ?? '';

        return $query->where(function (Builder $q) use ($like, $digitsOnly, $amountColumn) {
            $q->whereRaw('CAST(id_pos AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(id_transaksi AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(no_referensi AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(nama_pengirim AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(nama_penerima AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(nomor_identitas AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw('CAST(telepon_penerima AS TEXT) ILIKE ?', [$like])
                ->orWhereRaw("CAST({$amountColumn} AS TEXT) ILIKE ?", [$like])
                ->orWhereRaw('CAST(alamat_penerima AS TEXT) ILIKE ?', [$like]);

            // Receive POS: domicile address
            if ($amountColumn === 'bsubanyak') {
                $q->orWhereRaw('CAST(alamat_domisili_penerima AS TEXT) ILIKE ?', [$like]);
            }

            // Send POS also stores sender phone / addresses
            if ($amountColumn === 'amount') {
                $q->orWhereRaw('CAST(telpon_pengirim AS TEXT) ILIKE ?', [$like])
                    ->orWhereRaw('CAST(alamat_pengirim AS TEXT) ILIKE ?', [$like])
                    ->orWhereRaw('CAST(alamat_domisili_pengirim AS TEXT) ILIKE ?', [$like]);
            }

            if ($digitsOnly !== '') {
                $digitLike = '%'.$digitsOnly.'%';

                $q->orWhereRaw(
                    "REPLACE(REPLACE(REPLACE(CAST({$amountColumn} AS TEXT), ',', ''), '.', ''), ' ', '') ILIKE ?",
                    [$digitLike]
                )
                    ->orWhereRaw(
                        "regexp_replace(COALESCE(CAST(nomor_identitas AS TEXT), ''), '[^0-9]', '', 'g') LIKE ?",
                        [$digitLike]
                    )
                    ->orWhereRaw(
                        "regexp_replace(COALESCE(CAST(telepon_penerima AS TEXT), ''), '[^0-9]', '', 'g') LIKE ?",
                        [$digitLike]
                    );

                if ($amountColumn === 'amount') {
                    $q->orWhereRaw(
                        "regexp_replace(COALESCE(CAST(telpon_pengirim AS TEXT), ''), '[^0-9]', '', 'g') LIKE ?",
                        [$digitLike]
                    );
                }
            }
        });
    }
}
