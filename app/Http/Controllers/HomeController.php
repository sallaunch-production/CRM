<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Saldo;
use App\Models\SaldoHistory;
use App\Models\TransaksiSendPos;
use App\Models\TransaksiValid;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard ()
    {
        if(Auth::check()){
            $hariini=date('Y-m-d');
            $hariini2=date('Y-m-d h:m:s');
            $bullanini=date('Y-m');
            $hari1 = date('Y-m-d', strtotime($hariini . ' -1 day'));

            $query_saldoawal = SaldoHistory::where('tanggal','=',$hariini)->get();
            $query_saldoawal1 = SaldoHistory::where('tanggal','=',$hariini)->count();
            $query_saldoawal_min1 = SaldoHistory::where('tanggal','=',$hari1)->get();
            if(($query_saldoawal1 > 0)){
                $saldoawals = ($query_saldoawal[0]['awal'] ?? '0');
            }else{
                $akhir = $query_saldoawal_min1[0]['akhir'] ?? '';
                $max_id = SaldoHistory::orderBy('id','desc')->get();
                $idNya = $max_id[0]['id'];
                $idSaldo =  $idNya+1;
                $queref  = SaldoHistory::create([
                    'id' => $idSaldo,
                    'awal' =>  $akhir,
                    'akhir' => '',
                    'tanggal' => $hariini,
                ]);
        
                $saldoawals = ($query_saldoawal[0]['awal'] ?? '0');
            }
           
            $topuptoday = DB::table('saldo')->where('tanggal','LIKE','%'.$hariini.'%')->sum('nominal');

            $saldo_record_today =  TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','LIKE','%'.$hariini.'%')->get();
            $data_saldo_record_today = number_format($saldo_record_today[0]['sum']);
            
            $saldo_record_month = TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','LIKE','%'.$bullanini.'%')->get();
            $data_saldo_record_month = number_format($saldo_record_month[0]['sum']);

            $que_send =  DB::table('transaksi_send_pos')->where('tanggal','LIKE','%'.$hariini.'%')->count('no');

            $que_sendmonth =  DB::table('transaksi_send_pos')->where('tanggal','LIKE','%'.$bullanini.'%')->count('no');

            $send_today = TransaksiSendPos::select(DB::raw("sum(cast(replace(total_collect_amount, ',','') as float))"))->where('tanggal','LIKE','%'.$hariini.'%')->get();
            $data_send_today = number_format($send_today[0]['sum']);

            $saldo_send_month = TransaksiSendPos::select(DB::raw("sum(cast(replace(total_collect_amount, ',','') as float))"))->where('tanggal','LIKE','%'.$bullanini.'%')->get();
            $data_send_month = number_format($saldo_send_month[0]['sum']);

            $total_send_arsema_today=DB::table('transaksi_send')->where('tanggal','LIKE','%'.$hariini.'%')->count('no');
            
            $que_feedes2019_tier1 = TransaksiValid::where('tanggal','LIKE','%'.$hariini.'%')
                                                    ->where('bsubanyak','<=', '25000000')->count();
            $que_feedes2019_tier2 = TransaksiValid::where('tanggal','LIKE','%'.$hariini.'%')
                                                    ->where('bsubanyak', '>', '25000000')
                                                    ->where('bsubanyak','<=', '35000000')->count();
            $que_feedes2019_tier3 = TransaksiValid::where('tanggal','LIKE','%'.$hariini.'%')
                                                    ->where('bsubanyak','>', '35000000')
                                                    ->where('bsubanyak','<', '50000000')->count();
           
            $fee_tier1feedes2019= $que_feedes2019_tier1 * 25000;
            $fee_tier2feedes2019= $que_feedes2019_tier2 * 30000;
            $fee_tier3feedes2019= $que_feedes2019_tier3 * 35000;
            $totalfeedes2019=$fee_tier1feedes2019+$fee_tier2feedes2019+$fee_tier3feedes2019;

            $que_tvhariini =  TransaksiValid::where('tanggal','LIKE','%'.$hariini.'%')->count();
            $que_tvmonth = TransaksiValid::where('tanggal','LIKE','%'.$bullanini.'%')->count();

            $saldoakhir=(int)$saldoawals+(int)$topuptoday-(int)$saldo_record_today[0]['sum']- (int)($totalfeedes2019);
            $hasilsaldoakhir=number_format($saldoakhir);
           

            return view('dashboard.home-revisi', ['saldoawals' => $saldoawals, 'data_tvhariini' => $que_tvhariini, 'data_tvmonth' => $que_tvmonth,
            'totalfeedes2019'=> $totalfeedes2019, 'saldo_record_today' => $data_saldo_record_today,'saldo_record_month'=> $data_saldo_record_month,
            'data_sendhariini' => $que_send, 'data_send_arsema_today'=>$total_send_arsema_today, 'data_sendmonth'=> $que_sendmonth, 'data_send_today'=>$data_send_today,
            'data_send_month'=>$data_send_month,'datatopuptoday'=>$topuptoday,'hasilsaldoakhir'=>$hasilsaldoakhir
            ]);


        }

        return redirect('login')->with('You dont have access');
    }

    public function transaksi ()
    {
        if(Auth::check()){
            $hariini=date('Y-m-d');
            $hariini2=date('Y-m-d h:m:s');
            $bullanini=date('Y-m');
            $hari1 = date('Y-m-d', strtotime($hariini . ' -1 day'));

            $query_saldoawal = SaldoHistory::where('tanggal','=',$hariini)->get();
            $query_saldoawal1 = SaldoHistory::where('tanggal','=',$hariini)->count();
            $query_saldoawal_min1 = SaldoHistory::where('tanggal','=',$hari1)->get();
            if(($query_saldoawal1 > 0)){
                $saldoawals = $query_saldoawal[0]['awal'] ?? '';
            }else{
                $akhir = $query_saldoawal_min1[0]['akhir'] ?? '';
                $max_id = SaldoHistory::orderBy('id','desc')->get();
                $idNya = $max_id[0]['id'];
                $idSaldo =  $idNya+1;
                $queref  = SaldoHistory::create([
                    'id' => $idSaldo,
                    'awal' =>  $akhir,
                    'akhir' => '',
                    'tanggal' => $hariini,
                ]);
        
                $saldoawals = $query_saldoawal[0]['awal'] ?? '';
            }
           
            $topuptoday = DB::table('saldo')->where('tanggal','LIKE','%'.$hariini.'%')->sum('nominal');

            $saldo_record_today =  TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','LIKE','%'.$hariini.'%')->get();
            $data_saldo_record_today = number_format($saldo_record_today[0]['sum']);
            
            $saldo_record_month = TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','LIKE','%'.$bullanini.'%')->get();
            $data_saldo_record_month = number_format($saldo_record_month[0]['sum']);

            $que_send =  DB::table('transaksi_send_pos')->where('tanggal','LIKE','%'.$hariini.'%')->count('no');

            $que_sendmonth =  DB::table('transaksi_send_pos')->where('tanggal','LIKE','%'.$bullanini.'%')->count('no');

            $send_today = TransaksiSendPos::select(DB::raw("sum(cast(replace(total_collect_amount, ',','') as float))"))->where('tanggal','LIKE','%'.$hariini.'%')->get();
            $data_send_today = number_format($send_today[0]['sum']);

            $saldo_send_month = TransaksiSendPos::select(DB::raw("sum(cast(replace(total_collect_amount, ',','') as float))"))->where('tanggal','LIKE','%'.$bullanini.'%')->get();
            $data_send_month = number_format($saldo_send_month[0]['sum']);

            $total_send_arsema_today=DB::table('transaksi_send')->where('tanggal','LIKE','%'.$hariini.'%')->count('no');
            
            $que_feedes2019_tier1 = TransaksiValid::where('tanggal','LIKE','%'.$hariini.'%')
                                                    ->where('bsubanyak','<=', '25000000')->count();
            $que_feedes2019_tier2 = TransaksiValid::where('tanggal','LIKE','%'.$hariini.'%')
                                                    ->where('bsubanyak', '>', '25000000')
                                                    ->where('bsubanyak','<=', '35000000')->count();
            $que_feedes2019_tier3 = TransaksiValid::where('tanggal','LIKE','%'.$hariini.'%')
                                                    ->where('bsubanyak','>', '35000000')
                                                    ->where('bsubanyak','<', '50000000')->count();
           
            $fee_tier1feedes2019= $que_feedes2019_tier1 * 25000;
            $fee_tier2feedes2019= $que_feedes2019_tier2 * 30000;
            $fee_tier3feedes2019= $que_feedes2019_tier3 * 35000;
            $totalfeedes2019=$fee_tier1feedes2019+$fee_tier2feedes2019+$fee_tier3feedes2019;

            $que_tvhariini =  TransaksiValid::where('tanggal','LIKE','%'.$hariini.'%')->count();
            $que_tvmonth = TransaksiValid::where('tanggal','LIKE','%'.$bullanini.'%')->count();

            $saldoakhir=(int)$saldoawals+(int)$topuptoday-(int)$saldo_record_today[0]['sum']- (int)($totalfeedes2019);
            $hasilsaldoakhir=number_format($saldoakhir);
           

            return view('dashboard.home', ['saldoawals' => $saldoawals, 'data_tvhariini' => $que_tvhariini, 'data_tvmonth' => $que_tvmonth,
            'totalfeedes2019'=> $totalfeedes2019, 'saldo_record_today' => $data_saldo_record_today,'saldo_record_month'=> $data_saldo_record_month,
            'data_sendhariini' => $que_send, 'data_send_arsema_today'=>$total_send_arsema_today, 'data_sendmonth'=> $que_sendmonth, 'data_send_today'=>$data_send_today,
            'data_send_month'=>$data_send_month,'datatopuptoday'=>$topuptoday,'hasilsaldoakhir'=>$hasilsaldoakhir
            ]);


        }

        return redirect('login')->with('You dont have access');
    }

    
    public function transaksi_search(Request $request)
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

        if($tahun_dari == $tahunini){
            //$que_tv_belum=$mysqli->query("select count(no) as totalid from transaksi_valid where tanggal > '$sampai[0]-01-01 00:00:00' and tanggal < '$hari1 23:59:59'");
            $que_tv_belum = TransaksiValid::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','>',$hari1.' 23:59:59')->count();
        }else{
            $que_tv_belum = TransaksiValid::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','>',$hari1.' 23:59:59')->count();
        }
        $data_tv_bulanan=$que_tv_belum;

        $saldo_record_2=TransaksiValid::select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->get();
        $data_bsu_2= number_format($saldo_record_2[0]['sum']);
        $que_send_bulanan=TransaksiSendPos::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->count();
        $data_send_bulanan=$que_send_bulanan;
        $saldo_send=TransaksiSendPos::select(DB::raw("sum(cast(replace(total_collect_amount, ',','') as float))"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->get();
        $data_send=number_format($saldo_send[0]['sum']);
        $quecek_saldo_awal=SaldoHistory::where('tanggal','=',$tanggal_dari)->get();
        $hasilsaldo=number_format($quecek_saldo_awal[0]['awal'] ?? '0');
        $quecek_saldo_akhir=SaldoHistory::where('tanggal','=',$tanggal_sampai)->get();
        $hasilsaldoakhir=number_format($quecek_saldo_akhir[0]['akhir']);
        $topuptoday_s=Saldo::select(DB::raw("sum((nominal))"))->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->get();
        $datatopuptoday_s=number_format($topuptoday_s[0]['sum']);

        if($tahun_dari == $tahunini){
                 $que_tv_bulanan=TransaksiValid::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->count();
            }
            else{
                 $que_tv_bulanan=TransaksiValid::where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->count();
            }
           $data_tv_bulanan=$que_tv_bulanan;
           $now=($data_tv_bulanan/10000*100);

        return view('dashboard.transaksi_search', ['periode_dari'=>$periode_dari,'periode_sampai'=>$periode_sampai,'data_tv_bulanan'=>$data_tv_bulanan,'data_bsu_2'=>$data_bsu_2,
                    'data_send_bulanan'=>$data_send_bulanan,'data_send'=>$data_send,'hasilsaldo'=>$hasilsaldo,'hasilsaldoakhir'=>$hasilsaldoakhir,'datatopuptoday_s'=>$datatopuptoday_s,'now'=>$now
        ]);
      }
      return redirect('login')->with('You dont have access');
    }

}
