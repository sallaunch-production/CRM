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
use Illuminate\Support\Facades\Crypt;

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

/*
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
    }*/

    public function cetak_laporan_bi_g0001(Request $request)
    {
        $myfile = fopen(storage_path("app/G0001.txt"), "w") or die("Unable to open file!");
        // $myfile = fopen("G0001.txt", "w") or die("Unable to open file!");
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;
        $sampai=explode("-",$tanggal_sampai);
        $que=DB::table('transaksi_send')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima')
                                        ->UnionAll(DB::table('transaksi_send_pos')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=', $tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima'))
                                        ->UnionAll(DB::table('transaksi_send_clients')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=', $tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima'))->get();

        $jumlahtrans=DB::table('transaksi_send')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima')
                                          ->UnionAll(DB::table('transaksi_send_pos')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=', $tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima'))
                                          ->UnionAll(DB::table('transaksi_send_clients')->select('nama_pengirim','nama_penerima','negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=', $tanggal_sampai.' 23:59:59')->where('negara_penerima','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima'))->count();
         
        if($jumlahtrans<=9){
        $jumlahtrans="00000000".$jumlahtrans;
        }
        elseif($jumlahtrans<=99){
        $jumlahtrans="0000000".$jumlahtrans;
        }
        elseif($jumlahtrans<=999){
        $jumlahtrans="000000".$jumlahtrans;
        }
        elseif($jumlahtrans<=9999){
        $jumlahtrans="00000".$jumlahtrans;
        }
        elseif($jumlahtrans<=99999){
        $jumlahtrans="0000".$jumlahtrans;
        }
        elseif($jumlahtrans<=999999){
        $jumlahtrans="000".$jumlahtrans;
        }
        elseif($jumlahtrans<=9999999){
        $jumlahtrans="00".$jumlahtrans;
        }
        elseif($jumlahtrans<=99999999){
        $jumlahtrans="0".$jumlahtrans;
        }
        else{
        $jumlahtrans=$jumlahtrans;
        }
        $txt = "777958119M".$sampai[2].$sampai[1]."01G0001".$jumlahtrans."\n";
        fwrite($myfile, $txt);
        $no=0;		
        $line=1; 
        foreach ($que as $datatrans) {
            $i=0;
            $ij=0;
            $ijk=0;
            $ijkl=0;   
            $jumnp50=0;
            $jumnpenerima50=0;
            $frekuensi="";
            $namapengirim="";
            $namapenerima="";
            $banyakbsu="";
            $kodenegara = DB::table('negara_konverter')
            ->where('ppatk', 'ILIKE', '%' . $datatrans->negara_penerima . '%')
            ->orWhere('nama', 'ILIKE', $datatrans->negara_penerima)
            ->first();
            $jumnp=strlen($datatrans->nama_pengirim);
            $jumnp50=50-$jumnp;
            $kodenegaraBi=$kodenegara->bi ?? '';
            //echo "$jumnp | $jumnp50 | $i | ";
            while($i<$jumnp50){
            $namapengirim =$namapengirim." ";
            $i++;
            }			
            $dnamapengirim=$namapengirim.substr($datatrans->nama_pengirim, 0, 50);
            //echo "$dnamapengirim | ";echo strlen($dnamapengirim);echo"<br>";
            $jumnpenerima=strlen($datatrans->nama_penerima);
            $jumnpenerima50=50-$jumnpenerima;
            while($ij<$jumnpenerima50){
            $namapenerima=$namapenerima." ";
            $ij++;
            }			
            $dnamapenerima=$namapenerima.substr($datatrans->nama_penerima, 0, 50);
            
            $jumjumlah=strlen($datatrans->jumlah);
            $jumjumlah12=12-$jumjumlah;
            while($ijk<$jumjumlah12){
            $frekuensi=$frekuensi."0";
            $ijk++;
            }			
            $dfrekuensi=$frekuensi.$datatrans->jumlah;
            $bulat=ceil( $datatrans->sum);
                            $jumlahangka=strlen($bulat)-2;
                            $angkadepan=substr($bulat,0,$jumlahangka);
                                $angkabelakang=substr($bulat,-2);
                                if($angkabelakang>=50){
                                    $bsus=$bulat+(100-$angkabelakang);
                                    //$bsu=$angkadepan.'50';
                                }
                                else if($angkabelakang==00){
                                    $bsus=$bulat;
                                    //$bsu=$angkadepan.'50';
                                }
                                else{
                                    $bsus=$angkadepan.'50';
                                }
            $bsu=strlen($bsus);
            $jumbsu15=15-$bsu;
            while($ijkl<$jumbsu15){
            $banyakbsu=$banyakbsu."0";
            $ijkl++;
            }			
            $dbanyakbsu=$banyakbsu.$bsus;
            //echo "$dbanyakbsu | ";echo strlen($dbanyakbsu);echo"<br>";
            $tujuan="3";
            $txt = "0394".$kodenegaraBi.$dnamapenerima.$dnamapengirim.$dfrekuensi.$dbanyakbsu.$tujuan."\n";
            fwrite($myfile, $txt);
            $no++;		
            $line++;                           
        }
        fclose($myfile);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment');
        header("Content-Disposition: attachment; filename=G0001.txt");
        header("Content-Type: application/txt");
        header("Content-Transfer-Encoding: binary");
        readfile("G0001.txt");
        exit;



    }
    public function cetak_laporan_bi_baru_txt(Request $request)
    {
        //$keyAscii = "def0000078382b6b8d734e1d5063147d1ed67005073467204ec578c67006b9c9c5ee7b4df0f44723c45880dff8108a8eb119109ad334723ecc260b2f6a4373b8e192b558";
        //$key = Key::loadFromAsciiSafeString($keyAscii);
        $myfile = fopen(storage_path("app/G0002.txt"), "w") or die("Unable to open file!");
        //$myfile = fopen("G0002.txt", "w") or die("Unable to open file!");
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;
        $sampai=explode("-",$tanggal_sampai);
        $quer=DB::table('transaksi_valid')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima','negara_pengirim','id_pos')
                                          ->UnionAll(DB::table('transaksi_receive')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=', $tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos','negara_penerima'))
                                          ->UnionAll(DB::table('transaksi_receive_clients')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=', $tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos','negara_penerima'))
                                          ->UnionAll(DB::table('transaksi_sigue_cta')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(bsubanyak) as float"),DB::raw('COUNT(*) as jumlah'))->where('status','=','paid')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos'))->orderBy('nama_pengirim')->get();

        $jumlahtrans1=DB::table('transaksi_valid')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_penerima','negara_pengirim','id_pos')
                                          ->UnionAll(DB::table('transaksi_receive')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=', $tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos','negara_penerima'))
                                          ->UnionAll(DB::table('transaksi_receive_clients')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('deltawork','=','')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=', $tanggal_sampai.' 23:59:59')->where('negara_pengirim','<>','INDONESIA')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos','negara_penerima'))
                                          ->UnionAll(DB::table('transaksi_sigue_cta')->select('negara_pengirim','id_pos','nama_pengirim','nama_penerima',DB::raw("sum(bsubanyak) as float"),DB::raw('COUNT(*) as jumlah'))->where('status','=','paid')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('nama_pengirim','nama_penerima','negara_pengirim','id_pos'))->orderBy('nama_pengirim')->count();
        
        $que3=DB::table('goldstar_c2a')->select('sendername','nama_penerima',DB::raw("sum(cast(replace(targetamount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('status','=','Y')->groupBy('sendername','nama_penerima','tanggal')->orderBy('tanggal','desc')->get();
        $jumlah_transaksi3=DB::table('goldstar_c2a')->select('sendername','nama_penerima',DB::raw("sum(cast(replace(targetamount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('status','=','Y')->groupBy('sendername','nama_penerima','tanggal')->orderBy('tanggal','desc')->count();
                            
        $jumlahtrans=$jumlahtrans1+$jumlah_transaksi3;
        if($jumlahtrans<=9){
        $jumlahtrans="00000000".$jumlahtrans;
        }
        elseif($jumlahtrans<=99){
        $jumlahtrans="0000000".$jumlahtrans;
        }
        elseif($jumlahtrans<=999){
        $jumlahtrans="000000".$jumlahtrans;
        }
        elseif($jumlahtrans<=9999){
        $jumlahtrans="00000".$jumlahtrans;
        }
        elseif($jumlahtrans<=99999){
        $jumlahtrans="0000".$jumlahtrans;
        }
        elseif($jumlahtrans<=999999){
        $jumlahtrans="000".$jumlahtrans;
        }
        elseif($jumlahtrans<=9999999){
        $jumlahtrans="00".$jumlahtrans;
        }
        elseif($jumlahtrans<=99999999){
        $jumlahtrans="0".$jumlahtrans;
        }
        else{
        $jumlahtrans=$jumlahtrans;
        }
        $txt = "777958119M".$sampai[2].$sampai[1]."01G0002".$jumlahtrans."\n";
        fwrite($myfile, $txt); 
        $no=0;		
        $line=1; 
        foreach ($quer as $datatrans) {
            $i=0;
            $ij=0;
            $ijk=0;
            $ijkl=0;   
            $jumnp50=0;
            $jumnpenerima50=0;
            $frekuensi="";
            $namapengirim="";
            $namapenerima="";
            $banyakbsu="";

            if($datatrans->negara_pengirim=="COTE D'IVOIRE" || $datatrans->negara_pengirim=="Lao People's Democratic Republic"){
                $kodenegara= DB::table('negara_konverter')->where('nama','like','%'.$datatrans->negara_pengirim.'%')->first();
            }else{
                $kodenegara= DB::table('negara_konverter')->where('nama','=','%'.$datatrans->negara_pengirim.'%')->first();
               
            }

            $idpos= substr($datatrans->id_pos, 0, 5);
            $kodepos=DB::table('kp_konverter')->join('nopend_lama','nopend_lama.kprk','=','kp_konverter.kprk')->where('kpc','like',$idpos.'%')->first();
            $kodenegaraBi=$kodenegara->bi ?? '';
            $kodeposBi= $kodepos->bi ?? '';

            $jumnp=strlen(trim(str_replace('.','',$datatrans->nama_pengirim)));
            $jumnp50=50-$jumnp;
            while($i<$jumnp50){
                $namapengirim =$namapengirim." ";
                $i++;
            }
            
            $dnamapengirim=$namapengirim.substr(trim(str_replace('.','',$datatrans->nama_pengirim)), 0, 50);
            //echo "$dnamapengirim | ";echo strlen($dnamapengirim);echo"<br>";
            $jumnpenerima=strlen(str_replace('.','',trim($datatrans->nama_penerima)));
            $jumnpenerima50=49-$jumnpenerima;
            while($ij<$jumnpenerima50){
            $namapenerima=$namapenerima." ";
            $ij++;
            }			
            $dnamapenerima=$namapenerima.substr(str_replace('.','',trim($datatrans->nama_penerima)), 0, 50).' ';

            $jumjumlah=strlen($datatrans->jumlah);
            $jumjumlah12=12-$jumjumlah;
            while($ijk<$jumjumlah12){
            $frekuensi=$frekuensi."0";
            $ijk++;
            }			
            $dfrekuensi=$frekuensi.$datatrans->jumlah;

            $bsu=strlen(str_replace('.','',$datatrans->sum));
            $jumbsu15=15-$bsu;
            while($ijkl<$jumbsu15){
            $banyakbsu=$banyakbsu."0";
            $ijkl++;
            }			
            $dbanyakbsu=$banyakbsu.str_replace('.','',$datatrans->sum);
            //echo "$dbanyakbsu | ";echo strlen($dbanyakbsu);echo"<br>";

            $txt = $kodenegaraBi.$kodeposBi.$dnamapengirim.$dnamapenerima.$dfrekuensi.$dbanyakbsu."\n";
            fwrite($myfile, $txt);
            $no++;		
            $line++;
            }
            $no=0;		
            $line=1;
            foreach ($que3 as $datatrans3) {
                $plaintext_nama = Crypto::decrypt($datatrans3->nama_penerima, $key);
                   $i=0;
                $ij=0;
                $ijk=0;
                $ijkl=0;   
                $jumnp50=0;
                $jumnpenerima50=0;
                $frekuensi="";
                $namapengirim="";
                $namapenerima="";
                $banyakbsu="";
                   //echo"$datatrans[negara_pengirim]<br>";
                            
                $jumnp=strlen(trim($datatrans3->sendername));
                $jumnp50=50-$jumnp;
                //echo "$jumnp | $jumnp50 | $i | ";
                while($i<$jumnp50){
                $namapengirim =$namapengirim." ";
                $i++;
                }			
                $dnamapengirim=$namapengirim.substr(trim($datatrans3->sendername), 0, 50);
                //echo "$dnamapengirim | ";echo strlen($dnamapengirim);echo"<br>";
                $jumnpenerima=strlen(trim($plaintext_nama));
                $jumnpenerima50=49-$jumnpenerima;
                while($ij<$jumnpenerima50){
                $namapenerima=$namapenerima." ";
                $ij++;
                }			
                $dnamapenerima=$namapenerima.substr(trim($plaintext_nama), 0, 50).' ';
                
                $jumjumlah=strlen($datatrans3->jumlah);
                $jumjumlah12=12-$jumjumlah;
                while($ijk<$jumjumlah12){
                $frekuensi=$frekuensi."0";
                $ijk++;
                }			
                $dfrekuensi=$frekuensi.$datatrans3->jumlah;
                
                $bsu=strlen(str_replace('.','',$datatrans3->sum));
                $jumbsu15=15-$bsu;
                while($ijkl<$jumbsu15){
                $banyakbsu=$banyakbsu."0";
                $ijkl++;
                }			
                $dbanyakbsu=$banyakbsu.str_replace('.','',$datatrans3->sum);
                //echo "$dbanyakbsu | ";echo strlen($dbanyakbsu);echo"<br>";
                
                $txt = "TW0394".$dnamapengirim.$dnamapenerima.$dfrekuensi.$dbanyakbsu."\n";
                fwrite($myfile, $txt);
                $no++;		
                $line++;
                }
                fclose($myfile);
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment');
                header("Content-Disposition: attachment; filename=G0002.txt");
                header("Content-Type: application/txt");
                header("Content-Transfer-Encoding: binary");
                readfile("G0002.txt");
                exit;

    }

    public function cetak_laporan_bi_g0003(Request $request)
    {
        $myfile = fopen(storage_path("app/G0003.txt"), "w") or die("Unable to open file!");
        //$myfile = fopen("G0003.txt", "w") or die("Unable to open file!");
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;
        $sampai=explode("-",$tanggal_sampai);
        $que=DB::table('transaksi_valid')->select('nama_pengirim','negara_pengirim','kode_pos_penerima','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_pengirim','=','INDONESIA')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima','negara_pengirim')
                                         ->Union(DB::table('transaksi_receive')->select('nama_pengirim','negara_pengirim','kode_pos_penerima','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_pengirim','=','INDONESIA')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima','negara_pengirim'))
                                         ->Union(DB::table('transaksi_receive_clients')->select('nama_pengirim','negara_pengirim','kode_pos_penerima','nama_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_pengirim','=','INDONESIA')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima','negara_pengirim'))->get();

        $jumlahtrans=DB::table('transaksi_valid')->select('nama_pengirim','negara_pengirim','kode_pos_penerima','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_pengirim','=','INDONESIA')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima','negara_pengirim')
                                         ->Union(DB::table('transaksi_receive')->select('nama_pengirim','negara_pengirim','kode_pos_penerima','nama_penerima',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_pengirim','=','INDONESIA')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima','negara_pengirim'))
                                         ->Union(DB::table('transaksi_receive_clients')->select('nama_pengirim','negara_pengirim','kode_pos_penerima','nama_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(*) as jumlah'))->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_pengirim','=','INDONESIA')->groupBy('nama_pengirim','nama_penerima','kode_pos_penerima','negara_pengirim'))->count();
        $tgllengkap="G0003";
      
                
        if($jumlahtrans<=9){
            $jumlahtrans="00000000".$jumlahtrans;
            }
            elseif($jumlahtrans<=99){
            $jumlahtrans="0000000".$jumlahtrans;
            }
            elseif($jumlahtrans<=999){
            $jumlahtrans="000000".$jumlahtrans;
            }
            elseif($jumlahtrans<=9999){
            $jumlahtrans="00000".$jumlahtrans;
            }
            elseif($jumlahtrans<=99999){
            $jumlahtrans="0000".$jumlahtrans;
            }
            elseif($jumlahtrans<=999999){
            $jumlahtrans="000".$jumlahtrans;
            }
            elseif($jumlahtrans<=9999999){
            $jumlahtrans="00".$jumlahtrans;
            }
            elseif($jumlahtrans<=99999999){
            $jumlahtrans="0".$jumlahtrans;
            }
            else{
            $jumlahtrans=$jumlahtrans;
            }

            $txt = "777958119M".$sampai[2].$sampai[1]."01G0003".$jumlahtrans."\n";
            fwrite($myfile, $txt);
            $no=0;		
            $line=1; 
            foreach ($que as $datatrans) {
                $i=0;
                $ij=0;
                $ijk=0;
                $ijkl=0;   
                $jumnp50=0;
                $jumnpenerima50=0;
                $frekuensi="";
                $namapengirim="";
                $namapenerima="";
                $banyakbsu="";
                $kodenegara=DB::table('negara_konverter')->where('nama','like','%'.$datatrans->negara_pengirim.'%')->get();
                $idpos= substr($datatrans->kode_pos_penerima, 0, 5);
                $kodepos=DB::table('kp_konverter')->join('nopend_lama','nopend_lama.kprk','=','kp_konverter.kprk')->where('kpc','like',$idpos.'%')->first();
                $jumnp=strlen($datatrans->nama_pengirim);
                $jumnp50=50-$jumnp;
                $kodeposBI=$kodepos->bi ?? '';
                while($i<$jumnp50){
                    $namapengirim =$namapengirim." ";
                    $i++;
                }	

                $dnamapengirim=$namapengirim.substr($datatrans->nama_pengirim, 0, 50);
                //echo "$dnamapengirim | ";echo strlen($dnamapengirim);echo"<br>";
                $jumnpenerima=strlen($datatrans->nama_penerima);
                $jumnpenerima50=50-$jumnpenerima;
                while($ij<$jumnpenerima50){
                $namapenerima=$namapenerima." ";
                $ij++;
                }	

                $dnamapenerima=$namapenerima.substr($datatrans->nama_penerima, 0, 50);
                
                $jumjumlah=strlen($datatrans->jumlah);
                $jumjumlah12=12-$jumjumlah;
                while($ijk<$jumjumlah12){
                $frekuensi=$frekuensi."0";
                $ijk++;
                }			
                $dfrekuensi=$frekuensi.$datatrans->jumlah;
                $bsu=strlen(str_replace('.','',$datatrans->sum));
                $bsu=strlen($datatrans->sum);
                $jumbsu15=15-$bsu;
                while($ijkl<$jumbsu15){
                $banyakbsu=$banyakbsu."0";
                $ijkl++;
                }			
                $dbanyakbsu=$banyakbsu.$datatrans->sum;
                //echo "$dbanyakbsu | ";echo strlen($dbanyakbsu);echo"<br>";
                $tujuan="3";
                $asal="0394";
                $txt = "$asal".$kodeposBI.$dnamapenerima.$dnamapengirim.$dfrekuensi.$dbanyakbsu.$tujuan."\n";
                fwrite($myfile, $txt);
                $no++;		
                $line++;
            }
            fclose($myfile);
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment');
            header("Content-Disposition: attachment; filename=G0003.txt");
                header("Content-Type: application/txt");
                header("Content-Transfer-Encoding: binary");
            readfile("G0003.txt");
            exit;
    }

  
    

    public function cetak_laporan_ppatk_baru(Request $request) 
    {
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;

        return Excel::download(new InvoicesExport($tanggal_dari,$tanggal_sampai), 'LTKL Incoming.xlsx');
    }

    public function cetak_laporan_ppatk_baru_c2a(Request $request) 
    {
        $tanggal_dari = $request->tanggal_dari;
        $tanggal_sampai = $request->tanggal_sampai;
        return Excel::download(new CetakLaporanPpatk($tanggal_dari,$tanggal_sampai), 'LTKL incoming C2A.xls');
    }
    
}