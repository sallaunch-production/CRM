<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\TransaksiSendPos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CetakLaporanNewCustomer implements FromView
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
        $tglDari=$this->tanggal_dari;
        $tglSampai=$this->tanggal_sampai;

        $querSend = DB::table('transaksi_send_pos as t1')
        ->select(
            DB::raw('MIN(t1.no) AS no'),
            't1.nama_pengirim',
            DB::raw('MIN(t1.no_referensi) AS no_referensi'),
            DB::raw('MIN(t1.tanggal) AS tanggal'),
            DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
            DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
            DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
            DB::raw('MIN(t1.kota_lahir_pengirim) AS kota_lahir_pengirim'),
            DB::raw('MIN(t1.alamat_pengirim) AS alamat_pengirim'),
            DB::raw('MIN(t1.telpon_pengirim) AS telpon_pengirim'),
            DB::raw('MIN(t1.negara_penerima) AS negara_penerima'),
            DB::raw('MIN(t1.kode_pos_domisili_pengirim) AS kode_pos_domisili_pengirim')
        )
        ->leftJoin('transaksi_send_pos as t2', function($join) use ($tglDari) {
            $join->on('t1.nama_pengirim', '=', 't2.nama_pengirim')
                  ->where('t2.tanggal', '<=', $this->tanggal_dari . ' 23:59:59');
        })
        ->where('t1.tanggal', '>=', $this->tanggal_dari . ' 00:00:00')
        ->where('t1.tanggal', '<=', $this->tanggal_sampai . ' 23:59:59')
        ->whereNull('t2.nama_pengirim') // Filter nama_pengirim yang tidak ada di t2
        ->groupBy('t1.nama_pengirim')
        ->get(); 




        $querReceive = DB::table('transaksi_valid as t1')
        ->select(
            DB::raw('MIN(t1.no) AS no'),
            't1.nama_penerima',
            DB::raw('MIN(t1.no_referensi) AS no_referensi'),
            DB::raw('MIN(t1.tanggal) AS tanggal'),
            DB::raw('MIN(t1.identitas) AS identitas'),
            DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
            DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
            DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
            DB::raw('MIN(t1.alamat_penerima) AS alamat_penerima'),
            DB::raw('MIN(t1.kota_kelahiran) AS kota_kelahiran'),
            DB::raw('MIN(t1.telepon_penerima) AS telepon_penerima'),
            DB::raw('MIN(t1.negara_pengirim) AS negara_pengirim'),
            DB::raw('MIN(t1.kota_penerima) AS kota_penerima'),
            DB::raw('MIN(t1.pekerjaan) AS pekerjaan'),
            DB::raw('MIN(t1.id_pos) AS id_pos')
        )
        ->leftJoin('transaksi_valid as t2', function($join) use ($tglDari) {
            $join->on('t1.nama_penerima', '=', 't2.nama_penerima')
                  ->where('t2.tanggal', '<=', $this->tanggal_dari . ' 23:59:59');
        })
        ->where('t1.tanggal', '>=', $this->tanggal_dari . ' 00:00:00')
        ->where('t1.tanggal', '<=', $this->tanggal_sampai . ' 23:59:59')
        ->whereNull('t2.nama_penerima') // Filter nama_penerima yang tidak ada di t2
        ->groupBy('t1.nama_penerima')
        ->orderBy(DB::raw('MIN(t1.tanggal)'), 'desc') // Menambahkan agregat untuk tanggal di ORDER BY
        ->get(); 


        $quer=DB::table('transaksi_valid')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(no) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_penerima','nama_pengirim','negara_pengirim','id_pos')
                                          ->UnionAll(DB::table('transaksi_receive')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(no) as jumlah'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos'))
                                          ->UnionAll(DB::table('transaksi_receive_clients')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(no) as jumlah'))->where('deltawork','=','')->where('id_transaksi','<>','')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos'))
                                          ->UnionAll(DB::table('transaksi_sigue_cta')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(bsubanyak) as float"),DB::raw('COUNT(no) as jumlah'))->where('status','=','paid')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos'))
                                          ->orderBy('nama_pengirim')->get();
        $que3=DB::table('goldstar_c2a')->select('sendername','nama_penerima',DB::raw("sum(cast(replace(targetamount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=', $this->tanggal_sampai.' 23:59:59')->where('status','=','Y')->groupBy('sendername','nama_penerima','tanggal')->orderBy('tanggal','desc')->get();
                                       
        return view('excel.new_customer', [
            'que'=> $quer,'que3'=>$que3 ,'tanggal_dari'=> $this->tanggal_dari,'tanggal_sampai' =>  $this->tanggal_sampai,'querReceive'=>$querReceive,'querSend'=>$querSend
        ]);
    }
}
