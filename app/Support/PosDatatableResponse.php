<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PosDatatableResponse
{
    /**
     * Fast page response for large POS tables.
     * - Initial browse: skip expensive COUNT(*), use has-more paging.
     * - Search/filter: COUNT only filtered rows.
     *
     * @param  class-string  $modelClass
     * @param  string  $amountColumn  receive: bsubanyak, send: amount
     */
    public static function fromRequest(
        Request $request,
        string $modelClass,
        string $amountColumn,
        string $orderColumn = 'tanggal'
    ): array {
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        if ($length <= 0) {
            $length = 10;
        }

        $searchTerm = trim((string) $request->input('search.value', ''));
        $hasSearch = $searchTerm !== '';

        /** @var Builder $query */
        $query = $modelClass::query()->orderBy($orderColumn, 'desc');

        if ($hasSearch) {
            PosDatatableSearch::apply($query, $searchTerm, $amountColumn);
        }

        if ($hasSearch) {
            $recordsFiltered = (clone $query)->count();
            $recordsTotal = $recordsFiltered;
            $rows = $query->offset($start)->limit($length)->get();
        } else {
            // Browse mode: fetch one extra row instead of COUNT(*) on millions of rows.
            $rows = $query->offset($start)->limit($length + 1)->get();
            $hasMore = $rows->count() > $length;
            $rows = $rows->take($length)->values();
            $recordsFiltered = $start + $rows->count() + ($hasMore ? 1 : 0);
            $recordsTotal = $recordsFiltered;
        }

        $data = self::withRowIndex($rows, $start);

        return [
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];
    }

    protected static function withRowIndex(Collection $rows, int $start): Collection
    {
        return $rows->values()->map(function ($item, $index) use ($start) {
            $item->DT_RowIndex = $start + $index + 1;

            return $item;
        });
    }
}
