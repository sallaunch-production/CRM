<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ReportTransaksi;
use App\Exports\CetakLaporanBIg0001;
use App\Exports\CetakLaporanBIg0002;
use App\Exports\CetakLaporanBIg0003;
use App\Exports\CetakLaporanPpatk;
use App\Exports\Rekons;
use App\Exports\InvoicesExport;
use App\Exports\CetakLaporanBI;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Illuminate\Http\Response;

class ImportExportController extends Controller
{
     public function importExport()
    {
       return view('import');
    }

    public function export() 
    {
        return Excel::download(new ExportUsers, 'users.xlsx');
    }

    /*
    public function import() 
    {
        Excel::import(new ImportUsers, request()->file('file'));
            
        return back();
    }
    */

    public function cetak_invoce_search(Request $request) 
    {
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;
        return  Excel::download(new ReportTransaksi($tanggal_dari,$tanggal_sampai), 'TRANSAKSI SEND INVOICE.xlsx');
            
    }



    public function csvpilot(Request $request) 
    {
       
            $tanggal = $request->reservation;
            $dari = explode('/',$tanggal);
  
            $tahunini = date('Y');
            
            $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
            $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
            $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
    
            $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
            $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
            $tahun_sampai =  $dari[4];
            $namafile = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].$dari[0].$dari[1];

            $tgllengkap="POSARSEMA"."$namafile".date('His');
            $filename=$tgllengkap.'.csv';
            return  Excel::download(new Rekons($tanggal_dari,$tanggal_sampai), $tgllengkap.'.csv');
        
    }

    public function cetak_laporan_bi_baru(Request $request)
    {

        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

       
        return  Excel::download(new CetakLaporanBI($tanggal_dari,$tanggal_sampai), 'G0002.xls');
    }

    public function cetak_laporan_bi_g0001(Request $request)
    {

        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

       
        return  Excel::download(new CetakLaporanBIg0001($tanggal_dari,$tanggal_sampai), 'G0001.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
      ]);
    }

    public function cetak_laporan_bi_baru_txt(Request $request)
    {

        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

       
        return  Excel::download(new CetakLaporanBIg0002($tanggal_dari,$tanggal_sampai), 'G0002.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
      ]);
    }

    public function cetak_laporan_bi_g0003(Request $request)
    {

        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

       
        return  Excel::download(new CetakLaporanBIg0003($tanggal_dari,$tanggal_sampai), 'G0003.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
      ]);
    }

    

    public function cetak_laporan_ppatk_baru() 
    {
        return Excel::download(new InvoicesExport, 'LTKL.xlsx');
    }
    
}