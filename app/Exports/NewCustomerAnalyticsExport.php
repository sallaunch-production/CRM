<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NewCustomerAnalyticsExport implements FromView, ShouldAutoSize
{
    public function __construct(
        private readonly Collection $data,
        private readonly string $type,
        private readonly string $startDate,
        private readonly string $endDate
    ) {
    }

    public function view(): View
    {
        return view('excel.new_customer_analytics_excel', [
            'data' => $this->data,
            'type' => $this->type,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}
