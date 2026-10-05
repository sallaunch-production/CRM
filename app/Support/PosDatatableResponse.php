<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Reusable server-side DataTables response for large POS (and similar) lists.
 *
 * Pattern:
 * - Always page with limit/offset (UI shows N rows per page).
 * - Always return real recordsTotal / recordsFiltered so Info + pager are correct.
 * - Search applies to the full table, not the current page.
 * - Unfiltered totals are cached briefly to avoid COUNT(*) on every draw.
 */
class PosDatatableResponse
{
    /** Seconds to cache the unfiltered table total. */
    public const TOTAL_CACHE_SECONDS = 60;

    /**
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

        $recordsTotal = self::cachedTotal($modelClass);

        /** @var Builder $query */
        $query = $modelClass::query();

        if ($hasSearch) {
            PosDatatableSearch::apply($query, $searchTerm, $amountColumn);
            $recordsFiltered = (clone $query)->count();
        } else {
            $recordsFiltered = $recordsTotal;
        }

        $rows = $query
            ->orderBy($orderColumn, 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        return [
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => self::withRowIndex($rows, $start),
        ];
    }

    /**
     * @param  class-string  $modelClass
     */
    protected static function cachedTotal(string $modelClass): int
    {
        $table = (new $modelClass)->getTable();
        $cacheKey = 'crm.datatable.total.'.$table;

        return (int) Cache::remember($cacheKey, self::TOTAL_CACHE_SECONDS, function () use ($modelClass) {
            return (int) $modelClass::query()->count();
        });
    }

    protected static function withRowIndex(Collection $rows, int $start): Collection
    {
        return $rows->values()->map(function ($item, $index) use ($start) {
            $item->DT_RowIndex = $start + $index + 1;

            return $item;
        });
    }
}
