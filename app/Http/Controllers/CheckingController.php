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
use App\Models\TransaksiValid;
use DB;

class CheckingController extends Controller
{
    public function nama(){ 
        //  cek berhasil login 
        if(Auth::check()){
            
                return view('checking.nama');


        }

        return redirect('login')->with('You dont have access');
      }

      public function cari_nama(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
            $cari=$request->proses;
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
            $query=DB::table('transaksi_valid')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('nama_penerima','no')->get();
            
                return view('checking.nama', [
                    'query' => $query,'cari'=>$cari,'periode_dari'=>$periode_dari,'periode_sampai'=>$periode_sampai
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      public function profil(){ 
        //  cek berhasil login 
        if(Auth::check()){
            
                return view('checking.profil');


        }

        return redirect('login')->with('You dont have access');
      }

      public function search_profil(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
              $nama=$request->nama;
              $berdasarkan=$request->berdasarkan;
              $query=DB::table('profil')->where($berdasarkan,'LIKE','%'.$nama.'%')->orderBy('nama','desc')->get();

            
                return view('checking.profil',[
                    'dat'=>$query,'nama'=>$nama
                ]);


        }

        return redirect('login')->with('You dont have access');
      }


      public function sales(){ 
        //  cek berhasil login 
        if(Auth::check()){
            
                return view('checking.sales');


        }

        return redirect('login')->with('You dont have access');
      }


      public function newcustomer(){ 
        //  cek berhasil login 
        if(Auth::check()){
            
                return view('checking.newcustomer');


        }

        return redirect('login')->with('You dont have access');
      }
}

