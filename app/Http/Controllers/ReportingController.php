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
use Yajra\DataTables\Facades\Datatables;
use App\Models\TransaksiValid;
use App\Models\Saldo;
use App\Models\TransaksiSendPos;
use DB;
use Carbon\Carbon;

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


      
      public function history_refund(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu History Refund Arsema",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
          ]);
          $data = DB::table('transaksi_cancel')->whereNull('id_pos')->get(); 

                return view('reporting.history-refunds', [
                    'data' => $data
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      
      public function history_refund_pos(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu History Refund PT POS",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
          ]);
          $data = DB::table('transaksi_cancel')->whereNull('id_pos')->orderBy('no','desc')->get(); 

                return view('reporting.history_refund_pos', [
                    'data' => $data
             ]);


        }

        return redirect('login')->with('You dont have access');
      }


      
      public function history_amend_pos(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu History Amend PT POS",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
          ]);
          $data = DB::table('amend')->join('transaksi_send_pos','transaksi_send_pos.no_referensi','=','amend.no_referensi')->orderBy('amend.id','desc')->get(); 

                return view('reporting.history_amend_pos', [
                    'data' => $data
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      public function popimage(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu Upload Proof Of Payment",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
          ]);
          $data = DB::table('proof_of_payment')->orderBy('tanggal','desc')->get(); 

                return view('reporting.popimages', [
                    'data' => $data
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      
      
      public function list_country()
      { 
        //  cek berhasil login 
        if(Auth::check()){
        $list_country="ya";  
        $query = DB::table('high_risk_country')->get(); 
        return view('reporting.filtering', [
          'data'=>$query,'list_country'=>$list_country
             ]);

        }

        return redirect('login')->with('You dont have access');
      }
}
