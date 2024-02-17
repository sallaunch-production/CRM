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


      
      public function history_refund(){ 
        //  cek berhasil login 
        if(Auth::check()){

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

          $data = DB::table('amend')->join('transaksi_send_pos','transaksi_send_pos.no_referensi','=','amend.no_referensi')->orderBy('amend.id','desc')->get(); 

                return view('reporting.history_amend_pos', [
                    'data' => $data
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      
}
