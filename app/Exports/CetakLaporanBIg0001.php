<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\TransaksiSendPos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CetakLaporanBIg0001 implements FromView
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
        $tanggal = explode("-",$this->tanggal_dari);
        $sampai = explode("-",$this->tanggal_sampai);
        $tgl_sampai=$sampai[2].$sampai[1];
        $quer=DB::table('transaksi_send')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima')
                                          ->UnionAll(DB::table('transaksi_send_pos')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima'))->get();

        $quer_count=DB::table('transaksi_send')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima')
                                          ->UnionAll(DB::table('transaksi_send_pos')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima'))->count();
                                    
        return view('excel.g0001-txt', [
            'que'=> $quer,'jumlahtrans'=>$quer_count,'tanggal_dari'=> $this->tanggal_dari,'tanggal_sampai' =>  $this->tanggal_sampai,'tgl_sampai'=>$tgl_sampai
        ]);
    }
}
