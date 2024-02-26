<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\TransaksiSendPos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CetakLaporanPpatk implements FromView
{
    
    protected $tanggal_dari;
    protected $tanggal_sampai;
    private $table;

    public function __construct($table)
    {
     
       $this->table = $table;
    }

    public function view(): View
    {
       
        $tableHtml = $this->table;
                                 
        return view('excel.laporan-ppatk', [
            'tableHtml'=>$tableHtml
        ]);
    }
}
