<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\TransaksiSendPos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;


class ReportTransaksi implements FromView
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
        //$qur = DB::table('transaksi_send_pos')->join('transaksi_cancel','transaksi_cancel.no_referensi','=','transaksi_send_pos.no_referensi')->where('transaksi_send_pos.tanggal','>',$this->tanggal_dari.' 00:00:00')->where('transaksi_send_pos.tanggal','<',$this->tanggal_sampai.' 23:59:59')->orderBy('transaksi_send_pos.tanggal','asc')->get();
            
        $qur=TransaksiSendPos::where('tanggal','>',$this->tanggal_dari.' 00:00:00')->where('tanggal','<',$this->tanggal_sampai.' 23:59:59')->get();
        return view('excel.excel_report_invoice', [
            'datatrans' => $qur,'tanggal_dari'=> $this->tanggal_dari, 'tanggal_sampai' =>  $this->tanggal_sampai
            //'que_cancels' =>  DB::table('transaksi_cancel')->where('no_referensi','=',$bullanini)->get()
        ]);
    }
}
