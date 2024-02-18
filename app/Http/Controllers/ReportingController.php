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

      public function popimage(){ 
        //  cek berhasil login 
        if(Auth::check()){

          $data = DB::table('proof_of_payment')->orderBy('tanggal','desc')->get(); 

                return view('reporting.popimages', [
                    'data' => $data
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      
      public function sanction(Request $request)
      { 
          //  cek berhasil login 
          if(Auth::check()){
              if ($request->ajax()) {
                  $data = DB::table('sanction_list')->orderBy('id','desc')->get();
                  return Datatables::of($data)
                  ->addIndexColumn()
                  ->addColumn('action', function($row){
                      //$actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                      //return $actionBtn;
                  })
                  ->rawColumns(['action'])
                  ->make(true);
                }
                return view('sanctions.sanction');
              

          }

          return redirect('login')->with('You dont have access');
        }
      


        public function filtering()
        { 
          //  cek berhasil login 
          if(Auth::check()){
  
          return view('reporting.filtering', [
                     
               ]);
  
  
          }
  
          return redirect('login')->with('You dont have access');
        }


      public function cari_filtering(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          
          $tanggal = $request->reservation;
          $dari = explode('/',$tanggal);
          $cari_filtering_s="ya";
          $tahunini = date('Y');
          
          $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
  
          $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
          $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
          $tahun_sampai =  $dari[4];
          $namafile = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].$dari[0].$dari[1];

          $query = DB::table('transaksi_valid')->join('high_risk_country','high_risk_country.nama','=','transaksi_valid.negara_pengirim')->where('id_transaksi','<>','')->where('transaksi_valid.tanggal','>=',$tanggal_dari.' 00:00:00')->where('transaksi_valid.tanggal','<=',$tanggal_sampai.' 23:59:59')->orderBy('transaksi_valid.negara_pengirim','desc')->get(); 
                return view('reporting.filtering', [
                    'data' => $query,'cari_s'=>$cari_filtering_s
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
