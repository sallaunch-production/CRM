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

class NopendController extends Controller
{

    public function nopend(){ 
        if(Auth::check()){
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
            $ques=DB::table('nopend')->where('kpc','LIKE','%'.$nopend.'%')->count();
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
            $jum="";$ques="";
            return view('reporting.agent_sort',['jum'=>$jum,'ques'=>$ques]);
        }

        return redirect('login')->with('You dont have access');
      }


}

