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
use App\Models\TransaksiSendPos;
use DB;

class TransactionalController extends Controller
{

    public function transaksi(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
            $query = DB::table('transaksi_valid')->offset(10)
                ->limit(5)->orderBy('tanggal','desc')->get();
            return view('reporting.transaksi', [
                'hariini' => $hariini,'query'=>$query
             ]);

        }

        return redirect('login')->with('You dont have access');
      }


      public function transaksi_send_pos(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
            $query = TransaksiSendPos::orderBy('tanggal','desc')->get();
            return view('reporting.transaksi_send_pos', [
                'hariini' => $hariini,'query'=>$query
             ]);

        }

        return redirect('login')->with('You dont have access');
      }

}