<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\TransaksiSendPos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CetakLaporanBIg0003 implements FromView
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
        $quer=DB::table('transaksi_valid')->select('nama_pengirim','kode_pos_penerima','nama_penerima','negara_pengirim',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('negara_pengirim','=','INDONESIA')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima','negara_pengirim')
                                          ->UnionAll(DB::table('transaksi_receive')->select('nama_pengirim','kode_pos_penerima','nama_penerima','negara_pengirim',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->where('negara_pengirim','=','INDONESIA')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima','negara_pengirim'))->get();
        $querCount=DB::table('transaksi_valid')->select('nama_pengirim','kode_pos_penerima','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('negara_pengirim','=','INDONESIA')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima')
                                          ->UnionAll(DB::table('transaksi_receive')->select('nama_pengirim','kode_pos_penerima','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->where('negara_pengirim','=','INDONESIA')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima'))->count();         
        return view('excel.g0003-txt', [
            'que'=> $quer,'jumlahtrans'=> $querCount,'tanggal_dari'=> $this->tanggal_dari,'tanggal_sampai' =>  $this->tanggal_sampai,'tgl_sampai'=>$tgl_sampai
        ]);
    }
}
