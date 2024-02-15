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

    public function amend(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
            return view('transactional.amend', [
                'hariini' => $hariini
             ]);

        }

        return redirect('login')->with('You dont have access');
      }


      public function amend_pos(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
            return view('transactional.amendpos', [
                'hariini' => $hariini
             ]);

        }

        return redirect('login')->with('You dont have access');
      } 
      
      public function send_reversal(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
            return view('transactional.sendreversal', [
                'hariini' => $hariini
             ]);

        }

        return redirect('login')->with('You dont have access');
      }


      public function send_reversalpos(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
            return view('transactional.sendreversalpos', [
                'hariini' => $hariini
             ]);

        }

        return redirect('login')->with('You dont have access');
      }

}