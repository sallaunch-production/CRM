<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\TransaksiSendPos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CetakLaporanBIg0002 implements FromView
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
        $quer=DB::table('transaksi_valid')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima','negara_pengirim','id_pos')
                                          ->UnionAll(DB::table('transaksi_receive')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos','negara_penerima'))
                                          ->UnionAll(DB::table('transaksi_sigue_cta')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(bsubanyak) as float"),DB::raw('COUNT(*) as jumlah'))->where('status','=','paid')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos'))->orderBy('nama_pengirim')->get();

         $querCount=DB::table('transaksi_valid')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima','negara_pengirim','id_pos')
                                          ->UnionAll(DB::table('transaksi_receive')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos','negara_penerima'))
                                          ->UnionAll(DB::table('transaksi_sigue_cta')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(bsubanyak) as float"),DB::raw('COUNT(*) as jumlah'))->where('status','=','paid')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos'))->orderBy('nama_pengirim')->count();

        $quer_gold=DB::table('goldstar_c2a')->select('sendername','nama_penerima',DB::raw("sum(cast(replace(targetamount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->where('status','=','Y')->groupBy('sendername','nama_penerima','tanggal')->orderBy('tanggal','desc')->get();
        $querCountGold=DB::table('goldstar_c2a')->select('sendername','nama_penerima',DB::raw("sum(cast(replace(targetamount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->where('status','=','Y')->groupBy('sendername','nama_penerima','tanggal')->orderBy('tanggal','desc')->count();
                                    
        return view('excel.g0002-txt', [
            'que'=> $quer,'jumlahtrans_s'=>$quer_gold,'querCount'=> $querCount,'querCountGold'=>$querCountGold,'tanggal_dari'=> $this->tanggal_dari,'tanggal_sampai' =>  $this->tanggal_sampai,'tgl_sampai'=>$tgl_sampai
        ]);
    }
}
