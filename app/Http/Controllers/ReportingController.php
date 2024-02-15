<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\TransaksiReceive;
use App\Models\TransaksiSend;
use App\Models\TransaksiValid;
use App\Models\Saldo;
use App\Models\TransaksiSendPos;
use DB;

class ReportingController extends Controller
{
    //
    public function transaksi_direct(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $transaction = TransaksiReceive::orderBy('tanggal','asc')->get();
                return view('reporting.transaksi_direct', [
                    'transaction' => $transaction
             ]);


        }

        return redirect('login')->with('You dont have access');
      }


      
      public function transaksi_send(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $transaction = TransaksiSend::orderBy('tanggal','desc')->get();
                return view('reporting.transaksi_send', [
                    'transaction' => $transaction
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      public function transaksi_send_pos(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $transaction = TransaksiSendPos::orderBy('tanggal','asc')->get();
                return view('reporting.transaksi_send_pos', [
                    'transaction' => $transaction
             ]);


        }

        return redirect('login')->with('You dont have access');
      }


      public function transaksi_compare(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
            $tanggal_dari="";
            $qu = DB::table('countryinfo')->orderBy('countryName','asc')->get();
            
                return view('reporting.transaksi_compare', [
                    'hariini' => $hariini,'data'=>$qu,'tanggal_dari'=>$tanggal_dari
             ]);


        }

        return redirect('login')->with('You dont have access');
      }



      public function transaksi_compare_send(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $tanggal_dari="";
            $hariini = date("Y-m-d H:i:s");
            $qu = DB::table('countryinfo')->orderBy('countryName','asc')->get();
            
                return view('reporting.transaksi_compare_send', [
                    'hariini' => $hariini,'data'=>$qu,'tanggal_dari'=>$tanggal_dari
             ]);


        }

        return redirect('login')->with('You dont have access');
      }




      public function direct_report(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $send="";$receive="";
            $hariini = date("Y-m-d H:i:s");
                return view('reporting.direct_report', [
                    'hariini' => $hariini,'send'=>$send,'receive'=>$receive
             ]);

        }

        return redirect('login')->with('You dont have access');
      }


      public function report_pos(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
            $tanggal_dari="";
            $tanggal_sampai="";
                return view('reporting.report_pos', [
                    'hariini' => $hariini,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
             ]);

        }

        return redirect('login')->with('You dont have access');
      }


      public function report_pos_search(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          $tanggal = $request->reservation;
          $dari = explode('/',$tanggal);
          $tahunini = date('Y');
          
          $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
  
          $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
          $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
          $tahun_sampai =  $dari[4];
          $hariini = date("Y-m-d H:i:s");

          if($tahun_sampai >= $tahunini){
                    $que =  DB::table('transaksi_valid')->select(DB::raw('COUNT(*) AS id'))->select(DB::raw('regional'))->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))
                                    ->join('kode_pos','kode_pos.nopend','=','transaksi_valid.id_pos')
                                    ->where('tanggal', '>=', $tanggal_dari.' 00:00:00')
                                    ->where('tanggal', '<=', $tanggal_sampai.' 23:59:59')
                                    ->groupBy('regional')
                                    ->orderBy('regional','asc')
                                    ->get();

                    $ques= TransaksiValid::where('tanggal', '>=', $tanggal_dari.' 00:00:00')
                                          ->where('tanggal', '<=', $tanggal_sampai.' 23:59:59')
                                          ->whereNotIn('id_pos', function($q){
                                                  $q->select('nopend')->from('kode_pos');
                                              })->orderBy('id_pos','desc')->get();
                  
                    }
                    else{
                      $que =  DB::table('transaksi_valid')->select(DB::raw('COUNT(*) AS id'))->select(DB::raw('regional'))->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))
                                    ->join('kode_pos','kode_pos.nopend','=','transaksi_valid.id_pos')
                                    ->where('tanggal', '>=', $tanggal_dari.' 00:00:00')
                                    ->where('tanggal', '<=', $tanggal_sampai.' 23:59:59')
                                    ->groupBy('regional')
                                    ->orderBy('regional','asc')
                                    ->get();


                    $ques= TransaksiValid::where('tanggal', '>=', $tanggal_dari.' 00:00:00')
                                          ->where('tanggal', '<=', $tanggal_sampai.' 23:59:59')
                                          ->whereNotIn('id_pos', function($q){
                                                  $q->select('nopend')->from('kode_pos');
                                              })->orderBy('id_pos','desc')->get();
                     
                      }
                     
                      $angkaformat=number_format($que[0]['sum'] ?? '0');
              return view('reporting.report_pos', [
                    'hariini' => $hariini,'periode_dari'=>$periode_dari,'periode_sampai'=>$periode_sampai,'tanggal_dari'=>$tanggal_dari,
                    'tanggal_sampai'=>$tanggal_sampai,'dataques'=>$ques,'dataque'=>$que,'angkaformat'=>$angkaformat
             ]);

        }

        return redirect('login')->with('You dont have access');
      }


      public function mto_report(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $sigue = "";
          $moneygram="";
            $hariini = date("Y-m-d H:i:s");
                return view('reporting.mto_report', [
                    'hariini' => $hariini,'sigue'=>$sigue,'moneygram'=>$moneygram
             ]);

        }

        return redirect('login')->with('You dont have access');
      }

      public function report_send_invoice(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $periode_sampai ="";
            $hariini = date("Y-m-d H:i:s");
                return view('reporting.report_send_invoice', [
                    'hariini' => $hariini,'periode_sampai'=>$periode_sampai
             ]);

        }

        return redirect('login')->with('You dont have access');
      }



      public function rekons(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
                return view('reporting.rekons', [
                    'hariini' => $hariini
             ]);

        }

        return redirect('login')->with('You dont have access');
      }


      public function rekonssend(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
                return view('reporting.rekonssend', [
                    'hariini' => $hariini
             ]);

        }

        return redirect('login')->with('You dont have access');
      }


      public function send_invoice_search(Request $request)
      {
          if(Auth::check()){
            $tanggal = $request->reservation;
            $dari = explode('/',$tanggal);
           
            $hari1 = date('Y-m-d', strtotime($tanggal . ' -1 day'));
            $tahunini = date('Y');
            $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
            $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
            $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
    
            $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
            $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
    
              return view('reporting.report_send_invoice', [
                    'periode_dari' => $periode_dari,'periode_sampai'=>$periode_sampai,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
             ]);

  
          }
          return redirect('login')->with('You dont have access');
      }


      
      public function search_compare_receive(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          
          //pembanding1
          $negara = $request->negara;
          
          $tanggal = $request->reservation;
          $dari = explode('/',$tanggal);

          $tanggal_2 = $request->reservation2;
          $dari_2 = explode('/',$tanggal_2);

          $tahunini = date('Y');
          
          $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
  
          $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
          $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
          $tahun_sampai =  $dari[4];
          $hari1 = date('Y-m-d', strtotime($tanggal_sampai . ' -1 day'));
          if($tahun_dari == $tahunini){
            $que_tv_belum = DB::table('transaksi_valid')->where('tanggal','>',$tahun_sampai.'-01-01 00:00:00')->where('tanggal','<',$hari1.'23:59:59')->where('negara_pengirim','=',$negara)->count();
          }
          else{
           $que_tv_belum = DB::table('transaksi_valid')->where('tanggal','>',$tahun_sampai.'-01-01 00:00:00')->where('tanggal','<',$hari1.'23:59:59')->where('negara_pengirim','=',$negara)->count();
          }

          if($tahun_dari == $tahunini){
            $que_tv= DB::table('transaksi_valid')->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$hari1.'23:59:59')->where('negara_pengirim','=',$negara)->count();
          }
          else{
             $que_tv= DB::table('transaksi_valid')->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$hari1.'23:59:59')->where('negara_pengirim','=',$negara)->count();
          }

          if($tahun_dari == $tahunini){
            $que_tv_bulanan=TransaksiValid::where('tanggal','>',$tanggal_dari.'00:00:00')->where('tanggal','<',$tanggal_sampai.'23:59:59')->count();
            }
            else{
              $que_tv_bulanan=TransaksiValid::where('tanggal','>',$tanggal_dari.'00:00:00')->where('tanggal','<',$tanggal_sampai.'23:59:59')->count();
            }

          $fee_pos = $que_tv_bulanan * 25000;
          
          $topuptoday_s = Saldo::select(DB::raw("sum(nominal)"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->get();
          $datatopuptoday_s = number_format($topuptoday_s[0]['sum']??'0');

          $saldo_record=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','>',$tanggal_dari.'00:00:00')->where('tanggal','>',$tanggal_sampai.'23:59:59')->get();
          $data_bsu=number_format($saldo_record[0]['sum']??'0');
          
          
          //pembanding2///
          
          $tahun_dari_2 = $dari_2[2][0].$dari_2[2][1].$dari_2[2][2].$dari_2[2][3];
          $periode_dari_2 = $dari_2[1].'-'.$dari_2[0].'-'.$dari_2[2][0].$dari_2[2][1].$dari_2[2][2].$dari_2[2][3];
          $periode_sampai_2 = $dari_2[3].'-'.$dari_2[2][7].$dari_2[2][8].'-'.$dari_2[4];
  
          $tanggal_dari_2 = $dari_2[2][0].$dari_2[2][1].$dari_2[2][2].$dari_2[2][3].'-'.$dari_2[0].'-'.$dari_2[1];
          $tanggal_sampai_2 =  $dari_2[4].'-'.$dari_2[2][7].$dari_2[2][8].'-'.$dari_2[3];


          $saldo_record_2=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','>',$tanggal_dari_2.'00:00:00')->where('tanggal','<',$tanggal_sampai_2.'23:59:59')->get();
          $data_bsu_2=number_format($saldo_record_2[0]['sum']??'0');

          $topuptoday_s2=DB::table('saldo')->where('tanggal','>',$tanggal_dari_2.' 00:00:00')->where('tanggal','<',$tanggal_sampai_2.' 23:59:59')->sum('nominal');
          $datatopuptoday_s2 = number_format($topuptoday_s2[0]['sum']??'0');
         
          if($tahun_dari_2 == $tahunini){
            $que_tv_bulanan2 = TransaksiValid::where('tanggal','>',$tanggal_dari_2.'00:00:00')->where('tanggal','<',$tanggal_sampai_2.'23:59:59')->count();
          }
          else{
            $que_tv_bulanan2 = TransaksiValid::where('tanggal','>',$tanggal_dari_2.'00:00:00')->where('tanggal','<',$tanggal_sampai_2.'23:59:59')->count();
          }
          $fee_pos2 = $que_tv_bulanan2 * 25000;
          $hariini = date("Y-m-d H:i:s");
          $qu = DB::table('countryinfo')->orderBy('countryName','asc')->get();
          
                return view('reporting.transaksi_compare', [
                    'hariini' => $hariini,'data'=>$qu,'data_bsu'=>$data_bsu,'fee_pos'=>$fee_pos,'data_tv_bulanan'=>$que_tv_bulanan,'datatopuptoday_s'=>$datatopuptoday_s,'negara'=>$negara,'fee_pos2'=>$fee_pos2,'data_tv_bulanan_2'=>$que_tv_bulanan2,'datatopuptoday_s2'=>$topuptoday_s2,'data_bsu_2'=>$data_bsu_2,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari_2'=>$tanggal_dari_2,'tanggal_sampai_2'=>$tanggal_sampai_2
             ]);


        }

        return redirect('login')->with('You dont have access');
      }


      public function search_compare_send(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){

      //pembanding1
          $negara = $request->negara;
          
          $tanggal = $request->reservation;
          $dari = explode('/',$tanggal);

          $tanggal_2 = $request->reservation2;
          $dari_2 = explode('/',$tanggal_2);

          $tahunini = date('Y');
          
          $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
  
          $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
          $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
          $tahun_sampai =  $dari[4];
          $hari1 = date('Y-m-d', strtotime($tanggal_sampai . ' -1 day'));
         
          $que_tv_bulanan=TransaksiSendPos::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->where('negara_penerima','=',$negara)->count();
         
          $saldo_record=TransaksiSendPos::select(DB::raw("sum(cast(replace(total_collect_amount, ',','') as float))"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','>',$tanggal_sampai.' 23:59:59')->where('negara_penerima','=',$negara)->get();
          $data_bsu=number_format($saldo_record[0]['sum']??'0');

          $topuptoday_s = Saldo::select(DB::raw("sum(nominal)"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->get();
          $datatopuptoday_s = number_format($topuptoday_s[0]['sum']??'0');
          
          //pembanding2///
          
          $tahun_dari_2 = $dari_2[2][0].$dari_2[2][1].$dari_2[2][2].$dari_2[2][3];
          $periode_dari_2 = $dari_2[1].'-'.$dari_2[0].'-'.$dari_2[2][0].$dari_2[2][1].$dari_2[2][2].$dari_2[2][3];
          $periode_sampai_2 = $dari_2[3].'-'.$dari_2[2][7].$dari_2[2][8].'-'.$dari_2[4];
  
          $tanggal_dari_2 = $dari_2[2][0].$dari_2[2][1].$dari_2[2][2].$dari_2[2][3].'-'.$dari_2[0].'-'.$dari_2[1];
          $tanggal_sampai_2 =  $dari_2[4].'-'.$dari_2[2][7].$dari_2[2][8].'-'.$dari_2[3];


          $topuptoday_s2=DB::table('saldo')->where('tanggal','>',$tanggal_dari_2.' 00:00:00')->where('tanggal','<',$tanggal_sampai_2.' 23:59:59')->sum('nominal');
          $datatopuptoday_s2 = number_format($topuptoday_s2[0]['sum']??'0');

          $que_tv_bulanan2 = TransaksiSendPos::where('tanggal','>',$tanggal_dari_2.' 00:00:00')->where('tanggal','<',$tanggal_sampai_2.' 23:59:59')->where('negara_penerima','=',$negara)->count();
          
          $saldo_record_2=TransaksiSendPos::select(DB::raw("sum(cast(replace(total_collect_amount, ',','') as float))"))->where('tanggal','>',$tanggal_dari_2.' 00:00:00')->where('tanggal','<',$tanggal_sampai_2.' 23:59:59')->where('negara_penerima','=',$negara)->get();
          $data_bsu_2=number_format($saldo_record_2[0]['sum']??'0');

          $hariini = date("Y-m-d H:i:s");
          $qu = DB::table('countryinfo')->orderBy('countryName','asc')->get();
          
                return view('reporting.transaksi_compare_send', [
                    'hariini' => $hariini,'data'=>$qu,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari_2'=>$tanggal_dari_2,'tanggal_sampai_2'=>$tanggal_sampai_2,'data_tv_bulanan'=>$que_tv_bulanan,'data_bsu'=>$data_bsu,'datatopuptoday_s'=>$datatopuptoday_s,'data_tv_bulanan_2'=>$que_tv_bulanan2,'data_bsu_2'=>$data_bsu_2,'datatopuptoday_s2'=>$datatopuptoday_s2
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      public function direct_report_receive(Request $request){ 
          //  cek berhasil login 
          if(Auth::check()){
            $receive = "receive";
            $hariini = date("Y-m-d");
            $tahunini = date('Y');
            $que_tv_null = TransaksiValid::where('tanggal','>',$tahunini.'-01-01 00:00:00')->where('tanggal','<',$hariini.'23:59:59')->count();
            $saldo_record_null=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','>',$tahunini.'-01-01 00:00:00')->where('tanggal','<',$hariini.' 23:59:59')->get();
            $data_saldo_record_null = number_format($saldo_record_null[0]['sum']);

            return view('reporting.direct_report', [
              'receive' => $receive,'hariini'=>$hariini,'que_tv_null'=>$que_tv_null,'data_saldo_record_null'=>$data_saldo_record_null
             ]);
          }
          return redirect('login')->with('You dont have access');
      }


      public function direct_report_send(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          $send="send";
          $hariini = date("Y-m-d");
          $tahunini = date('Y');
          $que_tv = TransaksiSend::where('tanggal','>',$tahunini.'-01-01 00:00:00')->where('tanggal','<',$hariini.' 23:59:59')->count();
          $saldo_record=TransaksiSend::select(DB::raw("sum(cast(replace(amount, ',','') as float))"))->where('tanggal','>',$tahunini.'-01-01 00:00:00')->where('tanggal','<',$hariini.' 23:59:59')->get();
          $data_saldo_record = number_format($saldo_record[0]['sum']);

          return view('reporting.direct_report', [
            'send' => $send,'hariini'=>$hariini
           ]);
        }
        return redirect('login')->with('You dont have access');
    }

    
    public function direct_search_receive(Request $request){ 

          if(Auth::check()){
             $receive = "receive";
            $hariini = date("Y-m-d");
            $tanggal = $request->reservation;
            $dari = explode('/',$tanggal);
            $tahunini = date('Y');
            
            $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
            $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
            $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
    
            $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
            $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
            $tahun_sampai =  $dari[4];
            $hari1 = date('Y-m-d', strtotime($tanggal_sampai . ' -1 day'));

            $que_tv = TransaksiValid::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.'23:59:59')->count();
            $saldo_record=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->get();
            $data_saldo_record = number_format($saldo_record[0]['sum']);


            $que_tv_null = TransaksiValid::where('tanggal','>',$tahun_dari.'-01-01 00:00:00')->where('tanggal','<',$hariini.'23:59:59')->count();
            $saldo_record_null=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','>',$tahun_dari.'-01-01 00:00:00')->where('tanggal','<',$hariini.' 23:59:59')->get();
            $data_saldo_record_null = number_format($saldo_record_null[0]['sum']);
           

            return view('reporting.direct_report', [
              'receive' => $receive,'hariini'=>$hariini,'data_tv'=>$que_tv,'data_saldo_record'=>$data_saldo_record,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai,'que_tv_null'=>$que_tv_null,'data_saldo_record_null'=>$data_saldo_record_null
            ]);
          }
          return redirect('login')->with('You dont have access');

    }

    public function direct_search_send(Request $request){ 

      if(Auth::check()){
         $send = "send";
        $hariini = date("Y-m-d");
        $tanggal = $request->reservation;
        $dari = explode('/',$tanggal);
        $tahunini = date('Y');
        
        $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];

        $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
        $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
        $tahun_sampai =  $dari[4];
        $hari1 = date('Y-m-d', strtotime($tanggal_sampai . ' -1 day'));

        $que_tv = TransaksiSend::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->count();
        $saldo_record=TransaksiSend::select(DB::raw("sum(cast(replace(amount, ',','') as float))"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->get();
        $data_saldo_record = number_format($saldo_record[0]['sum']);


        $que_tv_null = TransaksiSend::where('tanggal','>',$tahunini.'-01-01 00:00:00')->where('tanggal','<',$hariini.' 23:59:59')->count();
        $saldo_record_null=TransaksiSend::select(DB::raw("sum(cast(replace(amount, ',','') as float))"))->where('tanggal','>',$tahunini.'-01-01 00:00:00')->where('tanggal','<',$hariini.' 23:59:59')->get();
        $data_saldo_record_null = number_format($saldo_record_null[0]['sum']);


        return view('reporting.direct_report', [
          'send' => $send,'hariini'=>$hariini,'data_tv'=>$que_tv,'data_saldo_record'=>$data_saldo_record,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai,'que_tv_null'=>$que_tv_null,'data_saldo_record_null'=>$data_saldo_record_null
        ]);
      }
      return redirect('login')->with('You dont have access');

    }


    public function mto_moneygram(Request $request)
    { 

          if(Auth::check()){
            $moneygram = "moneygram";
            $hariini = date("Y-m-d");
            $tahun=date('Y');
           
            $que_tv=TransaksiValid::where('tanggal','LIKE','%'.$hariini.'%')->count();
            $saldo_record=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','LIKE','%'.$hariini.'%')->get();
            $saldo_record_s = number_format($saldo_record[0]['sum']);
            return view('reporting.mto_report', [
              'moneygram' => $moneygram,'hariini'=>$hariini,'data_tv'=>$que_tv,'data_saldo_record'=>$saldo_record_s
            ]);
          }
          return redirect('login')->with('You dont have access');

    }


    public function mto_sigue(Request $request)
    { 
      if(Auth::check()){
        $sigue = "sigue";
        $hariini = date("Y-m-d");
        $tahun=date('Y');
        $que_tv=TransaksiValid::where('tanggal','>',$tahun.'-01-01 00:00:00')->where('tanggal','<',$hariini.' 23:59:59')->where('deltawork','=','ya')->where('sigue','=','yes')->count();
        $saldo_record=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','>',$tahun.'-01-01 00:00:00')->where('tanggal','<',$hariini.'23:59:59')->where('deltawork','=','ya')->where('sigue','=','yes')->get();
        $saldo_record_s = number_format($saldo_record[0]['sum']);

        return view('reporting.mto_report', [
          'sigue' => $sigue,'hariini'=>$hariini,'data_tv'=>$que_tv,'data_saldo_record'=>$saldo_record_s
        ]);
      }
      return redirect('login')->with('You dont have access');

    }

    public function mto_moneygram_search(Request $request)
    {
      if(Auth::check()){
        $moneygram = "moneygram";
        $hariini = date("Y-m-d");
        $tahun=date('Y');
        $tanggal = $request->reservation;
        $dari = explode('/',$tanggal);
        $tahunini = date('Y');
        
        $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];

        $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
        $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
        $tahun_sampai =  $dari[4];
        $hari1 = date('Y-m-d', strtotime($tanggal_sampai . ' -1 day'));

       
        $que_tv = TransaksiValid::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->whereNull('sigue')->count();
        $saldo_record=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->whereNull('sigue')->get();
        $saldo_record_s = number_format($saldo_record[0]['sum']);

        return view('reporting.mto_report', [
          'moneygram' => $moneygram,'hariini'=>$hariini,'data_tv'=>$que_tv,'data_saldo_record'=>$saldo_record_s,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
        ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function mto_sigue_search(Request $request)
    {
      if(Auth::check()){
        $sigue = "sigue";
        $hariini = date("Y-m-d");
        $tahun=date('Y');
        $tanggal = $request->reservation;
        $dari = explode('/',$tanggal);
        $tahunini = date('Y');
        
        $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];

        $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
        $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
        $tahun_sampai =  $dari[4];
        $hari1 = date('Y-m-d', strtotime($tanggal_sampai . ' -1 day'));
       
        $que_tv=TransaksiValid::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->where('sigue','=','yes')->where('deltawork','=','ya')->count();
        $saldo_record=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->where('sigue','=','yes')->where('deltawork','=','ya')->get();
        $saldo_record_s = number_format($saldo_record[0]['sum']);

        return view('reporting.mto_report', [
          'sigue' => $sigue,'hariini'=>$hariini,'data_tv'=>$que_tv,'data_saldo_record'=>$saldo_record_s,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
        ]);
      }
      return redirect('login')->with('You dont have access');
    }
}
