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
use Yajra\DataTables\Facades\Datatables;
use App\Models\TransaksiSend;
use App\Models\TransaksiSendPos;
use App\Models\TransaksiValid;
use DB;
use Carbon\Carbon;

class CheckingController extends Controller
{
    public function nama(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $saldo = DB::table('audit_trail')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu Customer",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
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
            $query=DB::table('transaksi_valid')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->groupBy('nama_penerima','no')->orderBy('nama_penerima','asc')->get();
            
                return view('checking.nama', [
                    'query' => $query,'cari'=>$cari,'periode_dari'=>$periode_dari,'periode_sampai'=>$periode_sampai
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      public function profil(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu Profile",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
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
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu Sales",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
                return view('checking.sales');
        }

        return redirect('login')->with('You dont have access');
      }


      public function search_sales(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          $tanggal = $request->reservation;
          $dari = explode('/',$tanggal);

          $tanggal_2 = $request->reservation2;
          $dari_2 = explode('/',$tanggal_2);

          $tahunini = date('Y');
          
          $tahun_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_dari = $dari[1].'-'.$dari[0].'-'.$dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3];
          $periode_sampai = $dari[3].'-'.$dari[2][7].$dari[2][8].'-'.$dari[4];
  
          $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
          $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
          $tahun_sampai =  $dari[4];
          $namafile = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].$dari[0].$dari[1];

          $tahun_dari_2 = $dari_2[2][0].$dari_2[2][1].$dari_2[2][2].$dari_2[2][3];
          $periode_dari_2 = $dari_2[1].'-'.$dari_2[0].'-'.$dari_2[2][0].$dari_2[2][1].$dari_2[2][2].$dari_2[2][3];
          $periode_sampai_2 = $dari_2[3].'-'.$dari_2[2][7].$dari_2[2][8].'-'.$dari_2[4];
  
          $tanggal_dari_2 = $dari_2[2][0].$dari_2[2][1].$dari_2[2][2].$dari_2[2][3].'-'.$dari_2[0].'-'.$dari_2[1];
          $tanggal_sampai_2 =  $dari_2[4].'-'.$dari_2[2][7].$dari_2[2][8].'-'.$dari_2[3];

          $quer= DB::table('transaksi_valid')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->whereNotIn('nama_penerima', function($q) use($tanggal_dari_2,$tanggal_sampai_2) {
                                              $q->select('nama_penerima')->from('transaksi_valid')->where('tanggal','>=',$tanggal_dari_2.' 00:00:00')->where('tanggal','<=',$tanggal_sampai_2.' 23:59:59')->whereNotNull('nama_penerima');
                                          })->groupBy('nama_penerima','no')->orderBy('tanggal','desc')->get();

                return view('checking.sales',[
                    'tanggal_dari_2'=>$tanggal_dari_2,'data'=>$quer,'tanggal_dari'=>$tanggal_dari,'tanggal_sampai'=>$tanggal_sampai
                ]);
        }

        return redirect('login')->with('You dont have access');
      }


      public function newcustomer(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu New Customer",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
                return view('checking.newcustomer');
        }

        return redirect('login')->with('You dont have access');
      }


      public function search_newcustomer(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          $tanggal = $request->reservation;
          $dari = explode('/',$tanggal);
          $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
          $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
          $tahun_sampai =  $dari[4];
          $quer=DB::table('transaksi_valid')->select('no','nama_penerima','no_referensi','tanggal','nomor_identitas','tanggal_lahir','alamat_penerima','telepon_penerima','id_pos')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->whereNotIn('nama_penerima', function($q) use($tanggal_dari,$tanggal_sampai) {
                                                $q->select('nama_penerima')->from('transaksi_valid')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->whereNotNull('nama_penerima');
                                            })->groupBy('nama_penerima','no')->UnionAll(DB::table('transaksi_sigue_cta')->select('no','nama_penerima','no_referensi','tanggal','nomor_identitas','tanggal_lahir','alamat_penerima','telepon_penerima','id_pos')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->whereNotIn('nama_penerima', function($q) use($tanggal_dari,$tanggal_sampai) {
                                              $q->select('nama_penerima')->from('transaksi_valid')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->whereNotNull('nama_penerima');
                                          }))->orderBy('tanggal','desc')->get();
         

          return view('checking.newcustomer',[
            'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari'=>$tanggal_dari,'data'=>$quer
          ]);


        }

        return redirect('login')->with('You dont have access');
      }


      public function screening(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu New Screening",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
          return view('checking.screening',[
            
          ]);


        }

        return redirect('login')->with('You dont have access');
      }

      public function cari_screening(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          $tanggal = $request->reservation;
          $dari = explode('/',$tanggal);
          $tanggal_dari = $dari[2][0].$dari[2][1].$dari[2][2].$dari[2][3].'-'.$dari[0].'-'.$dari[1];
          $tanggal_sampai =  $dari[4].'-'.$dari[2][7].$dari[2][8].'-'.$dari[3];
          $tahun_sampai =  $dari[4];
          $cari = "ya";
          $quer=DB::table('transaksi_valid')->where('deltawork','=','ya')->where('tanggal','>=',$tanggal_dari.' 00:00:00')->where('tanggal','<=',$tanggal_sampai.' 23:59:59')->orderBy('tanggal','desc')->get();
          
          return view('checking.screening',[
            'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari'=>$tanggal_dari,'data'=>$quer,'cari'=>$cari
          ]);


        }

        return redirect('login')->with('You dont have access');
      }

      public function sanction(Request $request)
      { 
          //  cek berhasil login 
          if(Auth::check()){
              $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu Sanction List",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' =>Carbon::now()->toDateTimeString(),
            ]);
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
      
        
        public function tambah_sanction(Request $request)
        { 
          if(Auth::check()){
            $tambah = "ya";
            return view('sanctions.sanction', [
                'tambah'=>$tambah,      
            ]);
        }

        return redirect('login')->with('You dont have access');
      }
    
      public function act_tambah_sanction(Request $request)
      { 
        if(Auth::check()){
          $nama = $request->nama;
          $alias = $request->alias;
          $tgl_lahir = $request->tgl_lahir;
          $alamat = $request->alamat;
          $asalnegara = $request->asalnegara;
          $nomor_identitas = $request->nomor_identitas;
          $telepon_penerima = $request->telepon_penerima;
          $keterangan = $request->keterangan;
          $kategori = $request->kategori;

          $qur = DB::table('sanction_list')->insertGetId([
            'nama' => $nama,
            'alias' => $alias,
            'tgl_lahir' => $tgl_lahir,
            'alamat' => $alamat,
            'asalnegara' => $asalnegara,
            'nomor_identitas' => $nomor_identitas,
            'telepon_penerima' => $telepon_penerima,
            'keterangan' => $keterangan,
            'kategori' => $kategori,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);


          
      }

      return redirect('login')->with('You dont have access');
    }
      


        public function filtering()
        { 
          //  cek berhasil login 
          if(Auth::check()){
            $qur = DB::table('audit_trail_crm')->insertGetId([
              'username' => Auth::user()->email,
              'url' => url()->current(),
              'action' => "Menu High Risk Country Transaction",
              'tanggal' => Carbon::now()->toDateTimeString(),
              'ip_address' => $_SERVER['REMOTE_ADDR'],
              'user_agent' => $_SERVER['HTTP_USER_AGENT'],
              'created_at' => Carbon::now()->toDateTimeString(),
              'updated_at' =>Carbon::now()->toDateTimeString(),
            ]);
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

}

