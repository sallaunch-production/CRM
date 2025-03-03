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
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransaksiValid;
use App\Models\Saldo;
use App\Models\TransaksiSendPos;
use App\Models\TransaksiReceiveAdisena;
use App\Models\TransaksiSendAdisena;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


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
          $data = DB::table('transaksi_cancel')->whereNull('id_pos')->orWhere('id_pos', '')->orderBy('no','desc')->get(); 
          $upload="";
                return view('reporting.history-refunds', [
                    'data' => $data,'upload'=>$upload
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
          $data = DB::table('transaksi_cancel')
                      ->whereNotNull('id_pos')
                      ->where('id_pos', '!=', '')
                      ->orderBy('no','desc')
                      ->get(); 

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
          $data = DB::table('amend')
                    ->join('transaksi_send_pos', 'transaksi_send_pos.no_referensi', '=', 'amend.no_referensi')
                    ->select('amend.*', 'transaksi_send_pos.tanggal','transaksi_send_pos.nama_penerima')  // Tambahkan kolom tanggal dari transaksi_send_pos
                    ->orderBy('amend.id', 'desc')
                    ->get();
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
            $nopends="";
            $cari="kosong";
            $query = DB::table('transaksi_valid')
                      ->select(
                          DB::raw("REPLACE(id_pos, ' ', '') as id_pos_sanitized"),
                          DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),
                          DB::raw("COUNT(no_referensi) as perbuatan")
                      )
                      ->where('deltawork', '=', 'ya')
                      ->where('id_pos', '<>', '')
                      ->groupBy(DB::raw("REPLACE(id_pos, ' ', '')"))
                      ->orderBy('perbuatan', 'desc')
                      ->get();
            return view('reporting.kota', [
              'data'=>$query, 'nopends'=>$nopends,'cari'=>$cari
             ]);
      }
      return redirect('login')->with('You dont have access');
    }

    
    public function detail_kota(Request $request)
    {
      if(Auth::check()){
          $id=$request->id;
          $cari="";
          //$query = DB::table('transaksi_valid')->where('deltawork','=','ya')->where('id_pos','=',$id)->orderBy('tanggal','desc')->get();
          $query = DB::table('transaksi_valid')
                      ->where('deltawork', '=', 'ya')
                      ->where(DB::raw("REPLACE(id_pos, ' ', '')"), '=', str_replace(' ', '', $id))
                      ->orderBy('tanggal', 'desc')
                      ->get();
          $nopends=substr($id,0,5);
          $datakotas = DB::table('nopend_lama')->where('kpc','LIKE',$nopends.'%')->first();
         
          return view('reporting.kota', [
            'data'=>$query,'cari'=>$cari,'datakotas'=>$datakotas,'nopends'=>$nopends
           ]);
      }
      return redirect('login')->with('You dont have access');
    }

    
    public function cari_kota(Request $request)
    {
      if(Auth::check()){
        $cari="ya"; $nopends="";
        $tanggal = $request->reservation;
        $dari = explode('/',$tanggal);
  
        $tahunini = date('Y');
        
        $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
  
        $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
        $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
        $query= DB::table('transaksi_valid')->select('id_pos',DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(transaksi_valid.no_referensi) as perbuatan'),'bsubanyak')->where('deltawork','=','ya')->where('id_pos','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('id_pos','bsubanyak')->orderBy('perbuatan','desc')->get();
        $databsu = DB::table('transaksi_valid')->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(no) as jumlah'))->where('deltawork','=','ya')->where('id_pos','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->first();
        $totalbsu=number_format($databsu->sum);
        $jumlah=number_format($databsu->jumlah);


        return view('reporting.kota', [
            'data'=>$query,'nopends'=>$nopends,'cari'=>$cari,'jumlah'=>$jumlah,'totalbsu'=>$totalbsu,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
           ]);
      }
      return redirect('login')->with('You dont have access');
    }


    public function tanggal_valid(Request $request)
    {
      if(Auth::check()){
        $tanggal_valid="ya";
        $tanggal_dari=$request->dari;
        $tanggal_sampai=$request->sampai;
        $id=$request->id;
        $query= DB::table('transaksi_valid')->where('deltawork','=','ya')->where('id_pos','=',$id)->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->orderBy('tanggal','desc')->get();
        //$databsu= DB::table('transaksi','transaksi_valid')->select(DB::raw("sum(transaksi.bsu) as float"),DB::raw('COUNT(transaksi_valid.no) as jumlah'))->where('transaksi_valid.deltawork','=','ya')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('transaksi_valid.id_pos','=',$id)->first();
        $databsu= TransaksiValid::with('transaksi')->select(DB::raw('COUNT(transaksi_valid.no) as jumlah'))
        ->whereHas('transaksi',function($q) use ($id){
            $q->select(DB::raw("sum(transaksi.bsu) as float"))->where('id',$id);
        })->where('transaksi_valid.deltawork','=','ya')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('transaksi_valid.id_pos','=',$id)->get();
       
        $totalbsu=number_format($databsu->totalbsu);
        $jumlah=number_format($databsu->jumlah);
        
        return view('reporting.kota', [
            'data'=>$query,'tanggal_valid'=>$tanggal_valid,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai,'totalbsu'=>$totalbsu,'jumlah'=>$jumlah
           ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function negara()
      {
        if(Auth::check()){
            $negara_pengirim="";
           
            $query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',  DB::raw("SUM(CAST(REPLACE(NULLIF(transaksi.bsu, ''), ',', '') AS FLOAT))"),DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->groupBy('transaksi_valid.negara_pengirim')->orderBy('perbuatan','desc')->get();
            return view('reporting.negara', [
              'data'=>$query,'negara_pengirim'=>$negara_pengirim,
             ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function negara_receive()
      {
        if(Auth::check()){
            $negara_pengirim="";
            $receive="ya";
            $query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',DB::raw("SUM(CAST(REPLACE(NULLIF(transaksi.bsu, ''), ',', '') AS FLOAT))"),DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->groupBy('transaksi_valid.negara_pengirim')->orderBy('perbuatan','desc')->get();
            return view('reporting.negara', [
              'data'=>$query,'negara_pengirim'=>$negara_pengirim,'receive'=>$receive
             ]);
      }
      return redirect('login')->with('You dont have access');
    }


      public function negara_send()
      {
        if(Auth::check()){
            $negara_penerima="";
            $send="ya";
            $query = DB::table('transaksi_send_pos')->select('negara_penerima',DB::raw("sum(cast(replace(amount, ',','') as float))"),DB::raw('COUNT(no) as perbuatan'))->groupBy('negara_penerima')->orderBy('perbuatan','desc')->get();
            return view('reporting.negara', [
              'data'=>$query,'negara_penerima'=>$negara_penerima,'send'=>$send
            ]);
        }
        return redirect('login')->with('You dont have access');
      }

      public function filtering_transaction()
      {
        if(Auth::check()){
            $query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',DB::raw("SUM(CAST(REPLACE(NULLIF(transaksi.bsu, ''), ',', '') AS FLOAT))"),DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->groupBy('transaksi_valid.negara_pengirim')->orderBy('perbuatan','desc')->get();
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
            'data'=>$query,'tanggal_dari'=> $tanggal_dari,'tanggal_sampai'=> $tanggal_sampai
          ]);
    }
    return redirect('login')->with('You dont have access');
  }

  public function laporan()
  {
    if(Auth::check()){
         return view('reporting.laporan', [
            
          ]);
    }
    return redirect('login')->with('You dont have access');
  }

  public function cari_laporan(Request $request)
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
     
         return view('reporting.laporan', [
          'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
          ]);
    }
    return redirect('login')->with('You dont have access');
  }

    public function filtering_identitas()
    {
      if(Auth::check()){
          $query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',DB::raw("SUM(CAST(REPLACE(NULLIF(transaksi.bsu, ''), ',', '') AS FLOAT))"),DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->groupBy('transaksi_valid.negara_pengirim')->orderBy('perbuatan','desc')->get();
          return view('reporting.filtering_identitas', [
            'data'=>$query,
          ]);
    }
      return redirect('login')->with('You dont have access');
    }

    public function filter_telepon()
    {
      if(Auth::check()){
          $query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',DB::raw("SUM(CAST(REPLACE(NULLIF(transaksi.bsu, ''), ',', '') AS FLOAT))"),DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->groupBy('transaksi_valid.negara_pengirim')->orderBy('perbuatan','desc')->get();
          return view('reporting.filter_telepon', [
            'data'=>$query,
          ]);
    }
      return redirect('login')->with('You dont have access');
    }


    public function filter_negara()
    {
      if(Auth::check()){
          $query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',DB::raw("SUM(CAST(REPLACE(NULLIF(transaksi.bsu, ''), ',', '') AS FLOAT))"),DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->groupBy('transaksi_valid.negara_pengirim')->orderBy('perbuatan','desc')->get();
          return view('reporting.filter_negara', [
            'data'=>$query,
          ]);
    }
      return redirect('login')->with('You dont have access');
    }



    public function filter_sender()
    {
      if(Auth::check()){
          $query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',DB::raw("SUM(CAST(REPLACE(NULLIF(transaksi.bsu, ''), ',', '') AS FLOAT))"),DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->groupBy('transaksi_valid.negara_pengirim')->orderBy('perbuatan','desc')->get();
          return view('reporting.filter_sender', [
            'data'=>$query,
          ]);
    }
      return redirect('login')->with('You dont have access');
    }
 
    
    public function search_filtering_identitas(Request $request)
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
        $query = DB::table('transaksi_valid')->select(DB::raw('COUNT(distinct nama_penerima) as perbuatan'),'identitas','nomor_identitas')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('nomor_identitas','identitas')->orderBy('perbuatan','desc')->get();
       
        return view('reporting.filtering_identitas', [
          'data'=>$query,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
        ]);

      }
      return redirect('login')->with('You dont have access');
    }

    public function detail_identitas(Request $request)
    {
      if(Auth::check()){
        $tanggal_dari =$request->dari;
        $tanggal_sampai = $request->sampai;
        $telpon = $request->telpon;
        $identitas = $request->identitas;

        $query=DB::table('transaksi_valid')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('nomor_identitas','=',$telpon)->where('identitas','=',$identitas)->orderBy('tanggal','desc')->get();
        return view('reporting.filtering_identitas', [
          'data'=>$query,'identitas'=>$identitas,'telpon'=>$telpon,'tanggal_dari_s'=>$tanggal_dari,'tanggal_sampai_s'=>$tanggal_sampai
        ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function search_filtering_telepon(Request $request)
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
        $query = DB::table('transaksi_valid')->select(DB::raw('COUNT(distinct nama_penerima) as perbuatan'),'telepon_penerima')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('telepon_penerima')->orderBy('perbuatan','desc')->get();
       
        return view('reporting.filter_telepon', [
          'data'=>$query,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
        ]);

      }
      return redirect('login')->with('You dont have access');
    }

    public function detail_telpon(Request $request)
    {
      if(Auth::check()){
        $tanggal_dari =$request->dari;
        $tanggal_sampai = $request->sampai;
        $telpon = $request->telpon;

        $query=DB::table('transaksi_valid')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('telepon_penerima','=',$telpon)->orderBy('tanggal','desc')->get();
        return view('reporting.filter_telepon', [
          'data'=>$query,'telpon'=>$telpon,'tanggal_dari_s'=>$tanggal_dari,'tanggal_sampai_s'=>$tanggal_sampai
        ]);
      }
      return redirect('login')->with('You dont have access');
    }

    
    public function search_filtering_negara(Request $request)
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

        $query = DB::table('transaksi_valid')->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),'nama_penerima',DB::raw('COUNT(distinct negara_pengirim) as perbuatan'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('nama_penerima','nomor_identitas')->get();
       
        return view('reporting.filter_negara', [
          'data'=>$query,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
        ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function detail_negara(Request $request)
    {
      if(Auth::check()){
        $tanggal_dari =$request->dari;
        $tanggal_sampai = $request->sampai;
        $nama_penerima = $request->nama_penerima; 
        $query = DB::table('transaksi_valid')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('nama_penerima','=',$nama_penerima)->orderBy('tanggal','desc')->get();

        return view('reporting.filter_negara', [
          'data'=>$query,'nama_penerima'=>$nama_penerima,'dari'=>$tanggal_dari,'sampai'=>$tanggal_sampai
        ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function search_filtering_sender(Request $request)
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

        $query = DB::table('transaksi_valid')->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),'nama_penerima',DB::raw('COUNT(distinct nama_pengirim) as perbuatan'),DB::raw('COUNT(distinct nomor_identitas) as noden'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('nama_penerima','nomor_identitas')->orderBy('perbuatan','desc')->get();
       
        return view('reporting.filter_negara', [
          'data'=>$query,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
        ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function detail_sender(Request $request)
    {
      if(Auth::check()){
        $tanggal_dari =$request->dari;
        $tanggal_sampai = $request->sampai;
        $nama_penerima = $request->nama_penerima; 
        $query = DB::table('transaksi_valid')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('nama_penerima','=',$nama_penerima)->orderBy('tanggal','desc')->get();

        return view('reporting.filter_sender', [
          'data'=>$query,'nama_penerima'=>$nama_penerima,'dari'=>$tanggal_dari,'sampai'=>$tanggal_sampai
        ]);
      }
      return redirect('login')->with('You dont have access');
   
    }

    
    public function proses_detail_negara(Request $request)
    {
      if(Auth::check()){
          $tanggal_dari="";
          $receive="ya";
          $negara_pengirim = $request->negara_pengirim;
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Filtering Detail Negara",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
         
         
            $query=DB::table('transaksi')->where('status','=','valid')->where('proses','=','sudah')->where('negara_pengirim','=',$negara_pengirim)->get();
           
           return view('reporting.negara')->with([
           'data'=>$query,'receive'=>$receive, 'negara_pengirim'=>$negara_pengirim,'tanggal_dari'=>$tanggal_dari
          ]);
        
      }
      return redirect('login')->with('You dont have access');
    }

    public function proses_detail_negara_send(Request $request)
    {
      if(Auth::check()){
          $tanggal_dari="";
          $send="ya";
          $negara_penerima = $request->negara_penerima;
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Filtering Detail Negara",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
         
         
            $query=DB::table('transaksi_send_pos')->where('negara_penerima','=',$negara_penerima)->orderBy('tanggal','desc')->get();
           
           return view('reporting.negara')->with([
           'data'=>$query, 'send'=>$send,'negara_penerima'=>$negara_penerima,'tanggal_dari'=>$tanggal_dari
          ]);
        
      }
      return redirect('login')->with('You dont have access');
    }

    public function pencarian_detail_negara(Request $request)
    {
      if(Auth::check()){
          $receive="ya";
          $pencarian_detail_negara="ya";
          $tanggal_dari=$request->dari;
          $tanggal_sampai=$request->sampai;
          $negara_pengirim = $request->negara_pengirim;
          $id_pos = $request->id_pos;
          $query=DB::table('transaksi_valid')->where('deltawork','=','ya')->where('negara_pengirim','=',$negara_pengirim)->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->orderBy('tanggal','desc')->get();
          $nopends=substr($id_pos,0,5);
          $datakotas= DB::table('nopend')->where('kpc','like',$nopends.'%')->first();
          $databsu=DB::table('transaksi_valid')->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(no) as jumlah'))->where('deltawork','=','ya')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_pengirim','=',$negara_pengirim)->first();
          $totalbsu=number_format($databsu->sum);
          $jumlah=number_format($databsu->jumlah);

          return view('reporting.negara', [
            'data'=>$query,'receive'=>$receive,'jumlah'=>$jumlah,'totalbsu'=>$totalbsu,'datakotas'=>$datakotas,'negara_pengirim'=>$negara_pengirim,'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari'=>$tanggal_dari,'pencarian_detail_negara'=>$pencarian_detail_negara
          ]);
      }
      return redirect('login')->with('You dont have access');
    }


    public function pencarian_detail_negara_send(Request $request)
    {
      if(Auth::check()){
          $send="ya";
          $pencarian_detail_negara_send="ya";
          $tanggal_dari=$request->dari;
          $tanggal_sampai=$request->sampai;
          $negara_penerima = $request->negara_penerima;
          $id_pos = $request->id_pos;
          $query=DB::table('transaksi_send_pos')->where('negara_penerima','=',$negara_penerima)->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->orderBy('tanggal','desc')->get();
          $nopends=substr($id_pos,0,5);
          $datakotas= DB::table('nopend')->where('kpc','like',$nopends.'%')->first();
          $databsu=DB::table('transaksi_send_pos')->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),DB::raw('COUNT(no) as jumlah'))->where('deltawork','=','ya')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->where('negara_penerima','=',$negara_penerima)->first();
          $totalbsu=number_format($databsu->sum);
          $jumlah=number_format($databsu->jumlah);

          return view('reporting.negara', [
            'data'=>$query,'jumlah'=>$jumlah,'totalbsu'=>$totalbsu,'datakotas'=>$datakotas,'negara_penerima'=>$negara_penerima,'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari'=>$tanggal_dari,'pencarian_detail_negara_send'=>$pencarian_detail_negara_send,'send'=>$send
          ]);
      }
      return redirect('login')->with('You dont have access');
    }

    

    public function cari_negara(Request $request)
    {
      if(Auth::check()){
        $tanggal = $request->reservation;
        $receive="ya";
        $dari = explode('/',$tanggal);
  
        $tahunini = date('Y');
        
        $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
        $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
  
        $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
        $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
       
        $databsu= DB::table('transaksi_valid')->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->first();
        $databsu2= DB::table('transaksi_valid')->select(DB::raw('COUNT(no) as jumlah'))->where('deltawork','=','ya')->where('id_pos','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->first();
              
        $totalbsu=number_format($databsu->sum);
        $jumlah=number_format($databsu2->jumlah);
        $query = DB::table('transaksi_valid')->select(DB::raw("sum(cast(replace(bsubanyak, ',','') as float))"),'negara_pengirim',DB::raw('COUNT(no) as perbuatan'))->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('negara_pengirim')->orderBy('perbuatan','desc')->get();
        //$query = DB::table('transaksi')->select('transaksi_valid.negara_pengirim',DB::raw("SUM(CAST(REPLACE(NULLIF(transaksi.bsu, ''), ',', '') AS FLOAT))"),'transaksi_valid.id_pos',DB::raw('COUNT(transaksi.no) as perbuatan'))->join('transaksi_valid','transaksi_valid.no_referensi','=','transaksi.no_referensi')->where('deltawork','=','ya')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('transaksi_valid.negara_pengirim','transaksi_valid.id_pos')->orderBy('perbuatan','desc')->get();
         
        return view('reporting.negara', [
          'data'=>$query,'receive'=>$receive,'jumlah'=>$jumlah,'totalbsu'=>$totalbsu,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
          ]);
    }
    return redirect('login')->with('You dont have access');
  }


      public function cari_negara_send(Request $request)
      {
        if(Auth::check()){
          $send="ya";
          $tanggal = $request->reservation;
          $dari = explode('/',$tanggal);

          $tahunini = date('Y');
          
          $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];

          $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
          $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
        
          $databsu= DB::table('transaksi_send_pos')->select(DB::raw("sum(cast(replace(amount, ',','') as float))"))->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->first();
          $databsu2= DB::table('transaksi_send_pos')->select(DB::raw('COUNT(no) as jumlah'))->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->first();
                
          $totalbsu=number_format($databsu->sum);
          $jumlah=number_format($databsu2->jumlah);
          $query = DB::table('transaksi_send_pos')->select(DB::raw("sum(cast(replace(amount, ',','') as float))"),'negara_penerima','id_pos',DB::raw('COUNT(no) as perbuatan'))->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('negara_penerima','id_pos')->orderBy('perbuatan','desc')->get();
        
          return view('reporting.negara', [
            'data'=>$query,'send'=>$send,'jumlah'=>$jumlah,'totalbsu'=>$totalbsu,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
            ]);
      }
      return redirect('login')->with('You dont have access');
    }
  
  public function screening_transaksi()
    {
      if(Auth::check()){

         return view('reporting.screening_transaksi', [
          
          ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function cari_screening_transaksi(Request $request)
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
        $query= DB::table('transaksi_send_pos')->select(DB::raw("sum(cast(replace(amount, ',','') as float))"),'nama_penerima','nama_pengirim',DB::raw('COUNT(nama_pengirim) as perbuatan'),DB::raw('COUNT(nomor_identitas) as noden'))->where('mgisession','<>','')->where('id_transaksi','<>','')->where('tanggal','>=',$tanggal_dari." 00:00:00")->where('tanggal','<=',$tanggal_sampai." 23:59:59")->groupBy('nama_pengirim','nomor_identitas','nama_penerima')->orderBy('perbuatan','desc')->get();
        
        return view('reporting.screening_transaksi', [
          'data'=>$query,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
          ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function page_history_refund(Request $request)
    {
      if(Auth::check()){
        $id= $request->id;
        $upload="ya";
        $qur = DB::table('transaksi_cancel')->where('no','=',$id)->first();
         $no_referensi = $qur->no_referensi;


        return view('reporting.history-refunds', [
          'data'=>$qur,'upload'=>$upload,'no_referensi'=>$no_referensi,'id'=>$id
          ]);
      }
      return redirect('login')->with('You dont have access');
    }

    public function upload_history_refund(Request $request)
    {
      if(Auth::check()){
        $id= $request->id;
        $gambar= $request->gambar;
        $alasan= $request->alasan;
        $validatedData =  $request->validate(['gambar' => 'required|mimes:jpeg,png,jpg,pdf|max:4048']);
        
        $name = $request->file('gambar')->getClientOriginalName();
        $filePath = 'pop/' . $name;

        $path = Storage::disk('public')->put($filePath, file_get_contents($request->gambar));
        $path = Storage::disk('public')->url($path);
        $maxId = DB::table('history_refund')->orderBy('id','desc')->first();

        $upload_file = DB::table('history_refund')->insertGetId([
          'id' =>  $maxId->id+1,
          'pop_image' => $name,
          'id_refund' =>$id,
          'alasan' =>  $alasan,
          'ip' => $_SERVER['REMOTE_ADDR'],
          'tanggal' => Carbon::now()->toDateTimeString(),
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString(),
      ]);

      return redirect('history_refund')->with('success','You have successfully uplaod pop');

      }
      return redirect('login')->with('You dont have access');
    }

    public function edit_history_refund(Request $request)
     {
      if(Auth::check()){
        $edit_history = "ya";
        $id_refund = $request->id_refund;
        $que = DB::table('history_refund')->where('id_refund','=',$id_refund)->first();
        $ques=DB::table('transaksi_cancel')->where('no','=',$id_refund)->first();

        return view('reporting.history-refunds', [
          'data'=>$que,'edit_history'=>$edit_history,'datas'=>$ques,'id_refund'=>$id_refund
          ]);
      }
         return redirect('login')->with('You dont have access');
    }
    
    public function edit_upload_history_refund(Request $request)
     {
      if(Auth::check()){
        $id= $request->id;
        $gambar= $request->gambar;
        $alasan= $request->alasan;
        
       if($gambar<>""){
        $validatedData =  $request->validate(['gambar' => 'required|mimes:jpeg,png,jpg,pdf|max:4048']);
        
        $name = $request->file('gambar')->getClientOriginalName();
        $filePath = 'pop/' . $name;

        $path = Storage::disk('public')->put($filePath, file_get_contents($request->gambar));
        $path = Storage::disk('public')->url($path);
          $edit = DB::table('history_refund')->where('id_refund','=',$id)->update([
            'pop_image' => $name,'alasan'=>$alasan,'tanggal'=> Carbon::now()->toDateTimeString(),
          ]);
          
       }else{
          $edit = DB::table('history_refund')->where('id_refund','=',$id)->update([
            'alasan'=>$alasan,'tanggal'=> Carbon::now()->toDateTimeString(),
          ]);
          
       }
        return redirect('history_refund')->with('success','You have successfully edit pop');
      }
      return redirect('login')->with('You dont have access');
    }



    public function delete_history_refund(Request $request)
    {
      if(Auth::check()){
        $id_refund =  $request->id_refund;
        $delete = DB::table('history_refund')->where('id_refund','=',$id_refund)->delete();
        return redirect('history_refund')->with('success','You have successfully delete');
      }
      return redirect('login')->with('You dont have access');
    }


    public function upload_refund_pos(Request $request)
    {
      if(Auth::check()){
        $no =  $request->no;
        $upload= "ya";
        $ques=DB::table('transaksi_cancel')->where('no','=',$no)->first();
        return view('reporting.history_refund_pos', [
          'data'=>$ques,'upload'=>$upload,'no'=>$no
          ]);
      }
      return redirect('login')->with('You dont have access');
    }



    public function act_upload_refund_pos(Request $request)
    {
      if(Auth::check()){
        $id= $request->id;
        $gambar= $request->gambar;
        $alasan= $request->alasan;
        $ip= $request->ip;
        
        $validatedData =  $request->validate(['gambar' => 'required|mimes:jpeg,png,jpg,pdf|max:4048']);
        
        $name = $request->file('gambar')->getClientOriginalName();
        $filePath = 'pop/' . $name;

        $path = Storage::disk('public')->put($filePath, file_get_contents($request->gambar));
        $path = Storage::disk('public')->url($path);
        $maxId = DB::table('history_refund')->orderBy('id','desc')->first();

        $upload_file = DB::table('history_refund')->insertGetId([
          'id' =>  $maxId->id+1,
          'pop_image' => $name,
          'id_refund' =>$id,
          'alasan' =>  $alasan,
          'ip' => $ip,
          'tanggal' => Carbon::now()->toDateTimeString(),
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString(),
      ]);
        if(Auth::user()->level=="compliance"){
           return redirect('history_refund_pos')->with('success','You have successfully upload');
          }elseif(Auth::user()->level=="operasional"){
           return redirect('history_refund_pos_operasional')->with('success','You have successfully upload');
        } 
      }
      return redirect('login')->with('You dont have access');
    }
    
    
    public function edit_refund_pos(Request $request)
    {
      if(Auth::check()){
        $id =  $request->id;
        $edit= "ya";
        $que = DB::table('history_refund')->where('id_refund','=',$id)->first();
        $ques =  DB::table('transaksi_cancel')->where('no','=',$id)->first();


        return view('reporting.history_refund_pos', [
          'data'=>$que,'datas'=>$ques,'edit'=>$edit,'id'=>$id
          ]);
      }
      return redirect('login')->with('You dont have access');
    }

    
    public function act_edit_refund_pos(Request $request)
    {
      if(Auth::check()){
        $id= $request->id;
        $gambar= $request->gambar;
        $alasan= $request->alasan;
        $ip= $request->ip;


        if($gambar<>""){
          $validatedData =  $request->validate(['gambar' => 'required|mimes:jpeg,png,jpg,pdf|max:4048']);
          
          $name = $request->file('gambar')->getClientOriginalName();
          $filePath = 'pop/' . $name;
  
          $path = Storage::disk('public')->put($filePath, file_get_contents($request->gambar));
          $path = Storage::disk('public')->url($path);
            $edit = DB::table('history_refund')->where('id_refund','=',$id)->update([
              'pop_image' => $name,'alasan'=>$alasan,'tanggal'=> Carbon::now()->toDateTimeString(),
            ]);
            
         }else{
            $edit = DB::table('history_refund')->where('id_refund','=',$id)->update([
              'alasan'=>$alasan,'tanggal'=> Carbon::now()->toDateTimeString(),'ip'=>$ip
            ]);
            
         }
          return redirect('history_refund_pos')->with('success','You have successfully edit pop');
        }
      return redirect('login')->with('You dont have access');
    }

    
    public function delete_refund_pos(Request $request)
    {
      if(Auth::check()){
        $id_refund =  $request->id;
        $delete = DB::table('history_refund')->where('id_refund','=',$id_refund)->delete();
        return redirect('history_refund_pos')->with('success','You have successfully delete');

      }
      return redirect('login')->with('You dont have access');
    }


    
    public function upload_pop(Request $request)
    {
      if(Auth::check()){  
        $upload = "ya";


         return view('reporting.popimages', [
          'upload'=>$upload,
          ]);
      }
      return redirect('login')->with('You dont have access');
    }


    public function act_upload_pop(Request $request)
    {
      if(Auth::check()){  
        $gambar= $request->gambar;
        $tgl= $request->tgl;
        $noref= $request->noref;
        $nama= $request->nama;
        $jenis= $request->optradio;
        $ltkl= $request->ltkl;
        $ltkm= $request->ltkm;
        
        
        $validatedData =  $request->validate(['gambar' => 'required|mimes:jpeg,png,jpg,pdf|max:4048']);
        
        $name = $request->file('gambar')->getClientOriginalName();
        $filePath = 'pop/' . $name;

        $path = Storage::disk('public')->put($filePath, file_get_contents($request->gambar));
        $path = Storage::disk('public')->url($path);
        $maxId = DB::table('proof_of_payment')->orderBy('id','desc')->first();

        $upload_file = DB::table('proof_of_payment')->insertGetId([
          'id' =>  $maxId->id+1,
          'no_referensi' => $noref,
          'pop_image' =>$name,
          'tanggal_transaksi' =>  $tgl,
          'nama_client' => $nama,
          'jenis' => $jenis,
          'ltkl' => $ltkl,
          'ltkm' => $ltkm,
          'tanggal' => Carbon::now()->toDateTimeString(),
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString(),
      ]);
      if(Auth::user()->level=="compliance"){
        return redirect('popimage')->with('success','You have successfully upload');
      }elseif(Auth::user()->level=="operasional"){
        return redirect('popimage-operasional')->with('success','You have successfully upload');
      }
      
      }
      return redirect('login')->with('You dont have access');
    }

    
    public function edit_popimages(Request $request)
    {
      if(Auth::check()){  
        $edit_s="ya";
        $id = $request->id;
        $que= DB::table('proof_of_payment')->where('id','=',$id)->first();

        return view('reporting.popimages', [
          'data'=>$que,'id'=>$id,'edit_s'=>$edit_s
          ]);
      }
      return redirect('login')->with('You dont have access');
    }


    public function delete_popimages(Request $request)
    {
      if(Auth::check()){  
        $id =  $request->id;
        $delete = DB::table('proof_of_payment')->where('id','=',$id)->delete();
      
         if(Auth::user()->level=="compliance"){
          return redirect('popimage')->with('success','You have successfully delete');
        }elseif(Auth::user()->level=="operasional"){
          return redirect('popimage-operasional')->with('success','You have successfully delete');
        }
        
      }
      return redirect('login')->with('You dont have access');
    }


    
    public function act_edit_popimage(Request $request)
    {
      if(Auth::check()){  
        $id= $request->id;
        $noref = $request->noref;
        $gambar= $request->gambar;
        $tgl= $request->tgl;
        $nama= $request->nama;
        $jenis= $request->optradio;
        $ltkl= $request->ltkl;
        $ltkm= $request->ltkm;


        if($gambar<>""){
          $validatedData =  $request->validate(['gambar' => 'required|mimes:jpeg,png,jpg,pdf|max:4048']);
          
          $name = $request->file('gambar')->getClientOriginalName();
          $filePath = 'pop/' . $name;
  
          $path = Storage::disk('public')->put($filePath, file_get_contents($request->gambar));
          $path = Storage::disk('public')->url($path);
            $edit = DB::table('proof_of_payment')->where('id','=',$id)->update([
              'pop_image' => $name,'no_referensi'=>$noref,'tanggal_transaksi'=>$tgl,'nama_client'=>$nama,'jenis'=>$jenis,'ltkl'=>$ltkl,'ltkm'=>$ltkm,'tanggal'=>Carbon::now()->toDateTimeString()
            ]);
            
         }else{
            $edit = DB::table('proof_of_payment')->where('id','=',$id)->update([
              'no_referensi'=>$noref,'tanggal_transaksi'=>$tgl,'nama_client'=>$nama,'jenis'=>$jenis,'ltkl'=>$ltkl,'ltkm'=>$ltkm,'tanggal'=>Carbon::now()->toDateTimeString()
            ]);
            
         }
         return redirect('popimage')->with('success','You have successfully update');
      }
      return redirect('login')->with('You dont have access');
    }




    public function transaction_receive_adisena(Request $request)
    { 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Transaksi Receive Adisena",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);


          if ($request->ajax()) {
                $query = DB::table('transaksi_receive_clients')
                // Gunakan JOIN berdasarkan agent_id yang ada di kedua tabel
                              ->leftJoin('agent_id', 'transaksi_receive_clients.agent_id', '=', 'agent_id.agent_id')
                              ->select(
                      'transaksi_receive_clients.*',  // Ambil semua kolom dari transaksi_receive_clients
                      'agent_id.nama as agent_nama'  // Ambil kolom 'nama' dari tabel agent_id dan beri alias 'agent_nama'
                  )
                  ->orderBy('transaksi_receive_clients.tanggal', 'desc');
                  $query->addSelect('transaksi_receive_clients.id_pos as transaksi_id_pos');
    
                          // Apply search filter
            if ($searchTerm = $request->input('search.value')) {
              $searchTerm = trim($searchTerm);

              $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_penerima', 'like', "%{$searchTerm}%")
                  ->orWhere('nama_pengirim', 'like', "%{$searchTerm}%")
                  ->orWhere('negara_pengirim', 'like', "%{$searchTerm}%")
                  ->orWhere('bsubanyak', 'like', "%{$searchTerm}%")
                  ->orWhere('no_referensi', 'like', "%{$searchTerm}%")
                  ->orWhere('identitas', 'like', "%{$searchTerm}%")
                  ->orWhere('nomor_identitas', 'like', "%{$searchTerm}%")
                  ->orWhere('tanggal_lahir', 'like', "%{$searchTerm}%")
                  ->orWhere('alamat_penerima', 'like', "%{$searchTerm}%")
                  ->orWhere('alamat_domisili_penerima', 'like', "%{$searchTerm}%")
                  ->orWhere('telepon_penerima', 'like', "%{$searchTerm}%")
                  ->orWhere('kota_penerima', 'like', "%{$searchTerm}%")
                  ->orWhere('pekerjaan', 'like', "%{$searchTerm}%")
                  ->orWhere('tujuan_penggunaan_dana', 'like', "%{$searchTerm}%")
                  ->orWhere('hubungan', 'like', "%{$searchTerm}%")
                  ->orWhere('kewarganegaraan', 'like', "%{$searchTerm}%")
                  ->orWhere('jenis_kelamin', 'like', "%{$searchTerm}%")
                  ->orWhere('kota_kelahiran', 'like', "%{$searchTerm}%")
                  ->orWhere('transaksi_receive_clients.id_pos', 'like', "%{$searchTerm}%")
                  ->orWhere('maker', 'like', "%{$searchTerm}%")
                  ->orWhere('approver', 'like', "%{$searchTerm}%")
                  ->orWhere('tanggal', 'like', "%{$searchTerm}%");
                })
                ->orWhere('agent_id.nama', 'like', "%{$searchTerm}%");
              
            }

            // Get total records without filtering
            $recordsTotal = DB::table('transaksi_receive_clients')->count();
            // Get total records after filtering
            $recordsFiltered = $query->count();

            // Apply pagination
            $data = $query->offset($request->input('start'))
                      ->limit($request->input('length'))
                      ->get();
                   
                      
             // Transform data to include DT_RowIndex
              $data = $data->map(function($item, $index) {
                $item->DT_RowIndex = $index + 1; // Add DT_RowIndex based on position
                return $item;
            });

            return response()->json([
                          'draw' => (int) $request->input('draw'),
                          'recordsTotal' => $recordsTotal,
                          'recordsFiltered' => $recordsFiltered,
                          'data' => $data
                      ]);
            }
          return view('reporting.transaksi-adisena');
        }

        return redirect('login')->with('You dont have access');
      }




      public function transaction_send_adisena(Request $request)
      { 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Transaksi Send Adisena",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
        if ($request->ajax()) {
          $query = DB::table('transaksi_send_clients')
          // Gunakan JOIN berdasarkan agent_id yang ada di kedua tabel
                        ->leftJoin('agent_id', 'transaksi_send_clients.agent_id', '=', 'agent_id.agent_id')
                        ->select(
                            'transaksi_send_clients.*',  // Ambil semua kolom dari transaksi_receive_clients
                            'agent_id.nama as agent_nama'  // Ambil kolom 'nama' dari tabel agent_id dan beri alias 'agent_nama'
                        )
                        ->orderBy('transaksi_send_clients.tanggal', 'desc');
                        $query->addSelect('transaksi_send_clients.id_pos as transaksi_id_pos');
    
          if ($searchTerm = $request->input('search.value')) {
            $searchTerm = trim($searchTerm);
            $query->where(function ($q) use ($searchTerm) {
              $q->where('no_referensi', 'like', "%{$searchTerm}%")
                ->orWhere('negara_penerima', 'like', "%{$searchTerm}%")
                ->orWhere('amount', 'like', "%{$searchTerm}%")
                ->orWhere('sending_fee', 'like', "%{$searchTerm}%")
                ->orWhere('total_collect_amount', 'like', "%{$searchTerm}%")
                ->orWhere('nama_penerima', 'like', "%{$searchTerm}%")
                ->orWhere('nama_pengirim', 'like', "%{$searchTerm}%")
                ->orWhere('identitas', 'like', "%{$searchTerm}%")
                ->orWhere('nomor_identitas', 'like', "%{$searchTerm}%")
                ->orWhere('tanggal_lahir', 'like', "%{$searchTerm}%")
                ->orWhere('kota_lahir_pengirim', 'like', "%{$searchTerm}%")
                ->orWhere('alamat_penerima', 'like', "%{$searchTerm}%")
                ->orWhere('telepon_penerima', 'like', "%{$searchTerm}%")
                ->orWhere('telpon_pengirim', 'like', "%{$searchTerm}%")
                ->orWhere('kota_penerima', 'like', "%{$searchTerm}%")
                ->orWhere('pekerjaan', 'like', "%{$searchTerm}%")
                ->orWhere('alamat_pengirim', 'like', "%{$searchTerm}%")
                ->orWhere('alamat_domisili_pengirim', 'like', "%{$searchTerm}%")
                ->orWhere('opt_display', 'like', "%{$searchTerm}%")
                ->orWhere('hubungan_pengirim', 'like', "%{$searchTerm}%")
                ->orWhere('negara_domisili_pengirim', 'like', "%{$searchTerm}%")
                ->orWhere('tujuan_penggunaan_dana', 'like', "%{$searchTerm}%")
                ->orWhere('kota_lahir_pengirim', 'like', "%{$searchTerm}%")
                ->orWhere('jenis_kelamin', 'like', "%{$searchTerm}%")
                ->orWhere('maker', 'like', "%{$searchTerm}%")
                ->orWhere('approver', 'like', "%{$searchTerm}%")
                ->orWhere('agent_id.nama', 'like', "%{$searchTerm}%");
              })
              ->orWhere('agent_id.nama', 'like', "%{$searchTerm}%");
        }
        $recordsTotal = TransaksiSendAdisena::count();
        $recordsFiltered = $query->count(); // Total records after filtering  
        
        // Apply pagination
      $data = $query->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();

         // Transform data to include DT_RowIndex
      $data = $data->map(function($item, $index) {
         $item->DT_RowIndex = $index + 1; // Add DT_RowIndex based on position
          return $item;
      });   

        return response()->json([
          'draw' => (int) $request->input('draw'),
          'recordsTotal' => $recordsTotal,
          'recordsFiltered' => $recordsFiltered,
          'data' => $data
      ]);
       }
    
       if ($request->ajax()) {
        $query = DB::table('transaksi_send_clients')
          // Gunakan JOIN berdasarkan agent_id yang ada di kedua tabel
                        ->leftJoin('agent_id', 'transaksi_send_clients.agent_id', '=', 'agent_id.agent_id')
                        ->select(
                            'transaksi_send_clients.*',  // Ambil semua kolom dari transaksi_receive_clients
                            'agent_id.nama as agent_nama'  // Ambil kolom 'nama' dari tabel agent_id dan beri alias 'agent_nama'
                        )
                        ->orderBy('transaksi_send_clients.tanggal', 'desc');
                        $query->addSelect('transaksi_send_clients.id_pos as transaksi_id_pos');
         // Apply search filter
        if ($searchTerm = $request->input('search.value')) {
          $searchTerm = trim($searchTerm);

          $query->where(function ($q) use ($searchTerm) {
            $q->where('no_referensi', 'like', "%{$searchTerm}%")
            ->orWhere('negara_penerima', 'like', "%{$searchTerm}%")
            ->orWhere('amount', 'like', "%{$searchTerm}%")
            ->orWhere('sending_fee', 'like', "%{$searchTerm}%")
            ->orWhere('total_collect_amount', 'like', "%{$searchTerm}%")
            ->orWhere('nama_penerima', 'like', "%{$searchTerm}%")
            ->orWhere('nama_pengirim', 'like', "%{$searchTerm}%")
            ->orWhere('identitas', 'like', "%{$searchTerm}%")
            ->orWhere('nomor_identitas', 'like', "%{$searchTerm}%")
            ->orWhere('tanggal_lahir', 'like', "%{$searchTerm}%")
            ->orWhere('kota_lahir_pengirim', 'like', "%{$searchTerm}%")
            ->orWhere('alamat_penerima', 'like', "%{$searchTerm}%")
            ->orWhere('telepon_penerima', 'like', "%{$searchTerm}%")
            ->orWhere('telpon_pengirim', 'like', "%{$searchTerm}%")
            ->orWhere('kota_penerima', 'like', "%{$searchTerm}%")
            ->orWhere('pekerjaan', 'like', "%{$searchTerm}%")
            ->orWhere('alamat_pengirim', 'like', "%{$searchTerm}%")
            ->orWhere('alamat_domisili_pengirim', 'like', "%{$searchTerm}%")
            ->orWhere('opt_display', 'like', "%{$searchTerm}%")
            ->orWhere('hubungan_pengirim', 'like', "%{$searchTerm}%")
            ->orWhere('negara_domisili_pengirim', 'like', "%{$searchTerm}%")
            ->orWhere('tujuan_penggunaan_dana', 'like', "%{$searchTerm}%")
            ->orWhere('kota_lahir_pengirim', 'like', "%{$searchTerm}%")
            ->orWhere('jenis_kelamin', 'like', "%{$searchTerm}%")
            ->orWhere('maker', 'like', "%{$searchTerm}%")
            ->orWhere('approver', 'like', "%{$searchTerm}%")
            ->orWhere('agent_id.nama', 'like', "%{$searchTerm}%");
          })
          ->orWhere('agent_id.nama', 'like', "%{$searchTerm}%");
        }

        $recordsTotal = TransaksiSendAdisena::count();
        $recordsFiltered = $query->count(); // Total records after filtering  
        
        // Apply pagination
        $data = $query->offset($request->input('start'))
                  ->limit($request->input('length'))
                  ->get();
               
                  
         // Transform data to include DT_RowIndex
          $data = $data->map(function($item, $index) {
            $item->DT_RowIndex = $index + 1; // Add DT_RowIndex based on position
            return $item;
        });
        
        return response()->json([
                      'draw' => (int) $request->input('draw'),
                      'recordsTotal' => $recordsTotal,
                      'recordsFiltered' => $recordsFiltered,
                      'data' => $data
                  ]);
        }
               return view('reporting.transaksi_send_adisena');
            

        }

        return redirect('login')->with('You dont have access');
      }
    
}
