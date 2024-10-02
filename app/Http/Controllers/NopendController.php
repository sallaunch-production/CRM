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
use App\Models\Saldo;
use App\Models\NegaraException;
use App\Models\TransaksiSend;
use App\Models\TransaksiSendPos;
use DB;
use Carbon\Carbon;

class NopendController extends Controller
{

    public function nopend(){ 
        if(Auth::check()){
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu Nopend/Id Pos",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' =>Carbon::now()->toDateTimeString(),
              ]);
            $jum="";$ques="";
            return view('reporting.nopend',['jum'=>$jum,'ques'=>$ques]);
        }

        return redirect('login')->with('You dont have access');
      }


    
    public function cari_nopend(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
            $nopend = $request->nopend;
            $jum=DB::table('agent_id')->where('id_pos','LIKE','%'.$nopend.'%')->count();
            $ques=DB::table('nopend')->where('kpc','LIKE',$nopend.'%')->count();
            $query = DB::table('agent_id')->where('id_pos','LIKE','%'.$nopend.'%')->get();
            $query_nopend = DB::table('nopend')->join('agent_id','agent_id.id_pos','=','nopend.kprk')->where('nopend.kpc','LIKE','%'.$nopend.'%')->get();
                return view('reporting.nopend', [
                    'jum' => $jum,'query'=>$query,'nopend'=>$nopend,'ques'=>$ques,'query_nopend'=>$query_nopend
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      public function agent_sort(){ 
        if(Auth::check()){
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu Agent Id",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' =>Carbon::now()->toDateTimeString(),
              ]);
            $tanggal_dari="";
            return view('reporting.agent_sort',['tanggal_dari'=>$tanggal_dari]);
        }

        return redirect('login')->with('You dont have access');
      }


      public function cari_agent(Request $request)
      {
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
            
            $jum=DB::table('transaksi_valid')->join('agent_id_test','agent_id_test.id_pos','=','transaksi_valid.id_pos')->where('tanggal','>',$tanggal_dari.' 00:00:00')->where('tanggal','<',$tanggal_sampai.' 23:59:59')->groupBy('agent_id_test.agent_id')->count();
            $jumkah=DB::table('transaksi_valid')->join('agent_id_test','agent_id_test.id_pos','=','transaksi_valid.id_pos')->groupBy('agent_id_test.agent_id')->count();
            $jumtv=DB::table('agent_id_test')->count();
            $belumaktif =$jumtv-$jumkah; 

            $quer=DB::table('transaksi_valid')->select('transaksi_valid.id_pos','agent_id_test.agent_id','agent_id_test.nama',DB::raw('count(agent_id_test.agent_id) as totalid'))->join('agent_id_test','agent_id_test.id_pos','=','transaksi_valid.id_pos')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('agent_id_test.agent_id','transaksi_valid.id_pos','agent_id_test.agent_id','agent_id_test.nama')->orderBy('totalid','desc')->get();

            return view('reporting.agent_sort',[
                'tanggal_dari'=>$tanggal_dari,'jum'=>$jum,'jumkah'=>$jumkah,'jumtv'=>$jumtv,'tanggal_sampai'=>$tanggal_sampai,'belumaktif'=>$belumaktif,'quer'=>$quer
            ]);
        }

        return redirect('login')->with('You dont have access');
      }


      public function liat_agent_belum_aktif(Request $request)
      {
        if(Auth::check()){
            $belumaktifnya="ya";
            $jum=""; $jumkah=""; $belumaktif="";
            $tanggal_dari = $request->tanggal_dari;
            $tanggal_sampai = "2023-01-10";
            $quer = DB::table('agent_id_test')
            ->whereNotIn('id_pos', function($q) use ($tanggal_sampai) {
                $q->select('id_pos')
                  ->from('transaksi_valid')
                  ->where('tanggal', '<=', $tanggal_sampai . ' 23:59:59')
                  ->whereNotNull('id_pos');
            })
            ->groupBy('id_pos', 'id')
            ->orderBy('id', 'desc')
            ->paginate(10);
            return view('reporting.agent_sort',[
               'quer'=>$quer,'tanggal_dari_s'=>$tanggal_dari,'belumaktifnya'=>$belumaktifnya,'belumaktif'=>$belumaktif,'tanggal_sampai'=>$tanggal_sampai,'jum'=>$jum,'jumkah'=>$jumkah
            ]);
        }

        return redirect('login')->with('You dont have access');
        }

}

