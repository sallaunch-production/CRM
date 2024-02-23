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
        $query = DB::table('high_risk_country')->orderBy('id','desc')->get(); 
        return view('reporting.filtering', [
          'data'=>$query,'list_country'=>$list_country
             ]);

        }

        return redirect('login')->with('You dont have access');
      }

      
       
      public function tambah_list_country()
      { 
        //  cek berhasil login 
        if(Auth::check()){
        $list_country=""; 
        $tambah_data="ya";
        $query = DB::table('countryinfo')->get(); 
        return view('reporting.filtering', [
          'data'=>$query,'list_country'=>$list_country,'tambah_data'=>$tambah_data
             ]);

        }

        return redirect('login')->with('You dont have access');
      }


      public function add_list_country(Request $request)
      { 
        //  cek berhasil login 
        if(Auth::check()){
        $list_country=""; 
        $tambah_data="ya";
        $negara_pengirim= $request->negara_pengirim;
        $query = DB::table('countryinfo')->get(); 
        $maxId = DB::table('high_risk_country')->orderBy('id','desc')->first();
        $cek_negara = DB::table('high_risk_country')->where('nama','=',$negara_pengirim)->count();
        if($cek_negara<=0){
          $idnya= (int)($maxId->id ?? '')+1;
          $qur = DB::table('high_risk_country')->insertGetId([
            'id' => $idnya,
            'nama' => $negara_pengirim,
            'tanggal' => Carbon::now()->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
          ]);
            return redirect('list_country')->with('success','Successfully add data.');
          }else{
            return redirect('list_country')->with('error','Negara sudah ada.');
          }
        
        }
        return redirect('login')->with('You dont have access');
      }

      public function delete_list_country(Request $request)
      {
        $id=$request->id;
        $delete = DB::table('high_risk_country')->where('id','=',$id)->delete();
        if($delete){
            return redirect('list_country')->with('success','Successfully delete data.');
          }else{
            return redirect('list_country')->with('success','failed delete data.');
          }
      }

      public function kota()
      {
        if(Auth::check()){
            $query = DB::table('transaksi_valid')->select('id_pos',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(no_referensi) as perbuatan'))->where('deltawork','=','ya')->where('id_pos','<>','')->groupBy('id_pos')->orderBy('perbuatan','desc')->get();
            return view('reporting.kota', [
              'data'=>$query,
             ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function negara()
      {
        if(Auth::check()){
            $query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',DB::raw("sum(cast(replace(transaksi.bsu, ',','') as float))"),DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->groupBy('transaksi_valid.negara_pengirim')->orderBy('perbuatan','desc')->get();
            return view('reporting.negara', [
              'data'=>$query,
             ]);
      }
      return redirect('login')->with('You dont have access');
    }

      public function filtering_transaction()
      {
        if(Auth::check()){
            $query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',DB::raw("sum(cast(replace(transaksi.bsu, ',','') as float))"),DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->groupBy('transaksi_valid.negara_pengirim')->orderBy('perbuatan','desc')->get();
            return view('reporting.filtering_transaction', [
              'data'=>$query,
            ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function search_filtering_transaction(Request $request)
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
        $query = DB::table('transaksi_valid')->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(no) as perbuatan'),'negara_pengirim','id_transaksi','nama_penerima','nama_pengirim','negara_pengirim','no_referensi','identitas','nomor_identitas','tanggal_lahir','alamat_penerima','telepon_penerima','kota_penerima','pekerjaan','id_pos','tanggal')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('negara_pengirim','id_transaksi','nama_penerima','nama_pengirim','negara_pengirim','no_referensi','identitas','nomor_identitas','tanggal_lahir','alamat_penerima','telepon_penerima','kota_penerima','pekerjaan','id_pos','tanggal')->orderBy('nama_penerima','desc')->get();
          return view('reporting.filtering_transaction', [
            'data'=>$query,'tanggal_dari'=> $tanggal_dari
          ]);
    }
    return redirect('login')->with('You dont have access');
  }

}
