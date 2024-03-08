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
    /*
    public function __construct($table)
    {
     
       $this->table = $table;
    }*/


    public function __construct($tanggal_dari, $tanggal_sampai)
    {
       $this->tanggal_dari = $tanggal_dari;
       $this->tanggal_sampai = $tanggal_sampai;
    }

    public function view(): View
    {
       
        //$tableHtml = $this->table;
        $tanggal = explode("-",$this->tanggal_dari);
        $sampai = explode("-",$this->tanggal_sampai);
        $tgl_sampai=$sampai[2].$sampai[1];
        $que = DB::table('transaksi_valid')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->orderBy('tanggal','asc')->get();
                          
        return view('excel.laporan-ppatk', [
           'datatran'=>$que,'tanggal_dari'=> $this->tanggal_dari,'tanggal_sampai' =>  $this->tanggal_sampai,
        ]);
    }
}
