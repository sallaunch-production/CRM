<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\TransaksiSendPos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class Rekons implements FromView
{
    
    protected $tanggal_dari;
    protected $tanggal_sampai;

    public function __construct($tanggal_dari, $tanggal_sampai)
    {
       $this->tanggal_dari = $tanggal_dari;
       $this->tanggal_sampai = $tanggal_sampai;
    }

    public function view(): View
    {
        $que=DB::table('transaksi_valid')->where('tanggal','>',$this->tanggal_dari.' 00:00:00')->where('tanggal','<',$this->tanggal_sampai.'23:59:59')->where('deltawork','=','ya')->get();
        return view('excel.csvpilot', [
            'que'=> $que ,'tanggal_dari'=> $this->tanggal_dari,'tanggal_sampai' =>  $this->tanggal_sampai
        ]);
    }
}
