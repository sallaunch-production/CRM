<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReturnCustomerExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $filterBy;
    protected $search;

    public function __construct(
        $startDate = null,
        $endDate = null,
        $filterBy = 'nama',
        $search = ''
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filterBy = $filterBy;
        $this->search = trim((string) $search);
    }

    public function query()
    {
        /*
        |--------------------------------------------------------------------------
        | Receive POS
        |--------------------------------------------------------------------------
        */
        $receivePos = DB::table('transaksi_valid')
            ->where('transaksi_valid.deltawork', 'ya')
            ->whereNotNull('transaksi_valid.id_transaksi')
            ->whereNotNull('transaksi_valid.nomor_identitas')
            ->whereRaw("trim(transaksi_valid.nomor_identitas) <> ''")
            ->when($this->startDate, function ($query) {
                $query->where(
                    'transaksi_valid.tanggal',
                    '>=',
                    $this->startDate
                );
            })
            ->when($this->endDate, function ($query) {
                $query->where(
                    'transaksi_valid.tanggal',
                    '<',
                    \Carbon\Carbon::parse($this->endDate)->addDay()
                );
            })
            ->select(
                DB::raw("trim(transaksi_valid.nomor_identitas) as nomor_identitas"),
                'transaksi_valid.nama_penerima as nama'
            );

        /*
        |--------------------------------------------------------------------------
        | Send POS
        |--------------------------------------------------------------------------
        */
        $sendPos = DB::table('transaksi_send_pos')
            ->whereNotNull('transaksi_send_pos.nomor_identitas')
            ->whereRaw("trim(transaksi_send_pos.nomor_identitas) <> ''")
            ->when($this->startDate, function ($query) {
                $query->where(
                    'transaksi_send_pos.tanggal',
                    '>=',
                    $this->startDate
                );
            })
            ->when($this->endDate, function ($query) {
                $query->where(
                    'transaksi_send_pos.tanggal',
                    '<',
                    \Carbon\Carbon::parse($this->endDate)->addDay()
                );
            })
            ->select(
                DB::raw("trim(transaksi_send_pos.nomor_identitas) as nomor_identitas"),
                'transaksi_send_pos.nama_pengirim as nama'
            );

        /*
        |--------------------------------------------------------------------------
        | Send Branch
        |--------------------------------------------------------------------------
        */
        $sendBranch = DB::table('transaksi_send')
            ->whereNotNull('transaksi_send.nomor_identitas')
            ->whereRaw("trim(transaksi_send.nomor_identitas) <> ''")
            ->when($this->startDate, function ($query) {
                $query->where(
                    'transaksi_send.tanggal',
                    '>=',
                    $this->startDate
                );
            })
            ->when($this->endDate, function ($query) {
                $query->where(
                    'transaksi_send.tanggal',
                    '<',
                    \Carbon\Carbon::parse($this->endDate)->addDay()
                );
            })
            ->select(
                DB::raw("trim(transaksi_send.nomor_identitas) as nomor_identitas"),
                'transaksi_send.nama_pengirim as nama'
            );

        /*
        |--------------------------------------------------------------------------
        | Receive Branch
        |--------------------------------------------------------------------------
        */
        $receiveBranch = DB::table('transaksi_receive')
            ->whereNotNull('transaksi_receive.nomor_identitas')
            ->whereRaw("trim(transaksi_receive.nomor_identitas) <> ''")
            ->when($this->startDate, function ($query) {
                $query->where(
                    'transaksi_receive.tanggal',
                    '>=',
                    $this->startDate
                );
            })
            ->when($this->endDate, function ($query) {
                $query->where(
                    'transaksi_receive.tanggal',
                    '<',
                    \Carbon\Carbon::parse($this->endDate)->addDay()
                );
            })
            ->select(
                DB::raw("trim(transaksi_receive.nomor_identitas) as nomor_identitas"),
                'transaksi_receive.nama_penerima as nama'
            );

        /*
        |--------------------------------------------------------------------------
        | Receive Client
        |--------------------------------------------------------------------------
        */
        $receiveClient = DB::table('transaksi_receive_clients')
            ->whereNotNull('transaksi_receive_clients.nomor_identitas')
            ->whereRaw("trim(transaksi_receive_clients.nomor_identitas) <> ''")
            ->when($this->startDate, function ($query) {
                $query->where(
                    'transaksi_receive_clients.tanggal',
                    '>=',
                    $this->startDate
                );
            })
            ->when($this->endDate, function ($query) {
                $query->where(
                    'transaksi_receive_clients.tanggal',
                    '<',
                    \Carbon\Carbon::parse($this->endDate)->addDay()
                );
            })
            ->select(
                DB::raw("trim(transaksi_receive_clients.nomor_identitas) as nomor_identitas"),
                'transaksi_receive_clients.nama_penerima as nama'
            );

        /*
        |--------------------------------------------------------------------------
        | Send Client
        |--------------------------------------------------------------------------
        */
        $sendClient = DB::table('transaksi_send_clients')
            ->whereNotNull('transaksi_send_clients.nomor_identitas')
            ->whereRaw("trim(transaksi_send_clients.nomor_identitas) <> ''")
            ->when($this->startDate, function ($query) {
                $query->where(
                    'transaksi_send_clients.tanggal',
                    '>=',
                    $this->startDate
                );
            })
            ->when($this->endDate, function ($query) {
                $query->where(
                    'transaksi_send_clients.tanggal',
                    '<',
                    \Carbon\Carbon::parse($this->endDate)->addDay()
                );
            })
            ->select(
                DB::raw("trim(transaksi_send_clients.nomor_identitas) as nomor_identitas"),
                'transaksi_send_clients.nama_pengirim as nama'
            );

        /*
        |--------------------------------------------------------------------------
        | UNION ALL
        |--------------------------------------------------------------------------
        */
        $allTransactions = $receivePos
            ->unionAll($sendPos)
            ->unionAll($sendBranch)
            ->unionAll($receiveBranch)
            ->unionAll($receiveClient)
            ->unionAll($sendClient);

        /*
        |--------------------------------------------------------------------------
        | GROUP BY ID NUMBER
        |--------------------------------------------------------------------------
        */
        $groupedCustomers = DB::query()
            ->fromSub($allTransactions, 'transactions')
            ->selectRaw("
                nomor_identitas,
                MAX(nama) AS nama,
                COUNT(*) AS count_transaction
            ")
            ->groupBy('nomor_identitas');

        /*
        |--------------------------------------------------------------------------
        | APPLY SEARCH
        |--------------------------------------------------------------------------
        */
        $customers = DB::query()
            ->fromSub($groupedCustomers, 'customers')
            ->when($this->search !== '', function ($query) {
                $query->where(
                    $this->filterBy,
                    'ILIKE',
                    '%' . $this->search . '%'
                );
            });

        /*
        |--------------------------------------------------------------------------
        | DEFAULT ORDER
        |--------------------------------------------------------------------------
        */
        $customers->orderByDesc('count_transaction');

        /*
        |--------------------------------------------------------------------------
        | EXCEL COLUMNS
        |--------------------------------------------------------------------------
        */
        return $customers->select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY count_transaction DESC) AS no'),
            'nomor_identitas',
            'nama',
            'count_transaction'
        );
    }

    public function headings(): array
    {
        return [
            'No',
            'ID Number',
            'Name',
            'Total Transactions',
        ];
    }
}