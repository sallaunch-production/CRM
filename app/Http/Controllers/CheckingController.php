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
use Yajra\DataTables\Facades\DataTables;
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
                //$query=DB::connection('mysql')->table('profil')->where($berdasarkan,'LIKE','%'.$nama.'%')->orderBy('nama','desc')->get();
                $query=DB::table('profil')->where($berdasarkan,'LIKE','%'.$nama.'%')->orderBy('nama','desc')->get();
            
                return view('checking.profil',[
                    'dat'=>$query,'nama'=>$nama
                ]);


        }

        return redirect('login')->with('You dont have access');
      }


      public function detail_transaksi(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
              $detail="ya";
              $nama=$request->nama;
              $tanggal_lahir=$request->tanggal_lahir;
              $nomor_identitas = trim((string) $request->nomor_identitas);
              // nama dinormalisasi: rapikan spasi ganda + trim (format nama antar tabel tidak konsisten)
              $namaNorm = preg_replace('/\s+/', ' ', trim((string) $nama));

              // Pencocokan orang lintas tabel: utamakan NIK (format konsisten & hampir selalu terisi),
              // fallback ke nama yang dinormalisasi bila NIK kosong. tanggal_lahir TIDAK dipakai karena
              // formatnya berbeda-beda antar tabel (MM/DD/YYYY vs YYYY-MM-DD vs DDMMYYYY) sehingga exact match gagal.
              $applyMatch = function($query, $nikCol, $namaCol) use ($nomor_identitas, $namaNorm) {
                  if ($nomor_identitas !== '') {
                      $query->whereRaw("trim($nikCol) = ?", [$nomor_identitas]);
                  } else {
                      $query->whereRaw("regexp_replace(btrim($namaCol), '\\s+', ' ', 'g') = ?", [$namaNorm]);
                  }
                  return $query;
              };

              // Tanpa JOIN ke tabel 'transaksi': join lama (INNER) membuang baris karena banyak
              // no_referensi tidak ada di 'transaksi' -> Receive Pos kosong. Tabel 'transaksi' juga
              // tidak ber-index no_referensi (berat). Kolom bsu & tgl_valid diambil dari transaksi_valid.
              $query  = DB::table('transaksi_valid')
              ->where('transaksi_valid.deltawork', 'ya')
              ->whereNotNull('transaksi_valid.id_transaksi');
              $applyMatch($query, 'transaksi_valid.nomor_identitas', 'transaksi_valid.nama_penerima');
              $query = $query->select(
                          'transaksi_valid.*',
                          DB::raw("replace(transaksi_valid.bsubanyak, ',', '') as bsu"),
                          DB::raw('transaksi_valid.tanggal as tgl_valid')
                       )->get();

              $querySendPos  = DB::table('transaksi_send_pos');
              $applyMatch($querySendPos, 'transaksi_send_pos.nomor_identitas', 'transaksi_send_pos.nama_pengirim');
              $querySendPos = $querySendPos->get();

              $querySend  = DB::table('transaksi_send');
              $applyMatch($querySend, 'transaksi_send.nomor_identitas', 'transaksi_send.nama_pengirim');
              $querySend = $querySend->get();

              $queryReceive  = DB::table('transaksi_receive');
              $applyMatch($queryReceive, 'transaksi_receive.nomor_identitas', 'transaksi_receive.nama_penerima');
              $queryReceive = $queryReceive->get();

              $queryReceiveClient  = DB::table('transaksi_receive_clients');
              $applyMatch($queryReceiveClient, 'transaksi_receive_clients.nomor_identitas', 'transaksi_receive_clients.nama_penerima');
              $queryReceiveClient = $queryReceiveClient->get();

              $querySendClient  = DB::table('transaksi_send_clients');
              $applyMatch($querySendClient, 'transaksi_send_clients.nomor_identitas', 'transaksi_send_clients.nama_pengirim');
              $querySendClient = $querySendClient->get();
            
                return view('checking.profil',[
                    'dat'=>$query,'nama'=>$nama,'detail'=>$detail,'querySendPos'=>$querySendPos,'querySend'=>$querySend,'queryReceive'=>$queryReceive,'queryReceiveClient'=>$queryReceiveClient,'querySendClient'=>$querySendClient
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
          $send=" ";
          $data= " ";
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
                return view('checking.newcustomer_new',['send'=>$send,'data'=>$data]);
        }

        return redirect('login')->with('You dont have access');
      }

      public function newcustomer_send(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $send="ya";
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu New Customer Send",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
                return view('checking.newcustomersend',['send'=>$send]);
        }

        return redirect('login')->with('You dont have access');
      }

public function newcustomer_all(){ 
        //  cek berhasil login 
        if(Auth::check()){
          $send="ya";
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Menu New Customer All",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
                return view('checking.newcustomerall',['send'=>$send]);
        }

        return redirect('login')->with('You dont have access');
      }

public function search_newcustomer(Request $request)
{
     if ($request->ajax()) {

        $reservation = str_replace('+-+', ' - ', $request->reservation);
        [$tanggal_dari, $tanggal_sampai] = explode(' - ', $reservation);

        $tanggal_dari = Carbon::createFromFormat('m/d/Y', trim($tanggal_dari))->format('Y-m-d');
        $tanggal_sampai = Carbon::createFromFormat('m/d/Y', trim($tanggal_sampai))->format('Y-m-d');

       // Gunakan DISTINCT ON (nama_penerima) untuk performa tinggi di PostgreSQL
        $query  = DB::table('transaksi_valid as t1')
          ->select(
              DB::raw('MIN(t1.no) AS no'),
              't1.nama_penerima',
              DB::raw('MIN(t1.no_referensi) AS no_referensi'),
              DB::raw('MIN(t1.tanggal) AS tanggal'),
              DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
              DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
              DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
              DB::raw('MIN(t1.kota_kelahiran) AS kota_kelahiran'),
              DB::raw('MIN(t1.alamat_penerima) AS alamat_penerima'),
              DB::raw('MIN(t1.telepon_penerima) AS telepon_penerima'),
              DB::raw('MIN(t1.negara_pengirim) AS negara_pengirim'),
              DB::raw('MIN(t1.kode_pos_penerima) AS kode_pos_penerima')
          )
          ->leftJoin('transaksi_valid as t2', function($join) use ($tanggal_dari) {
              $join->on('t1.nama_penerima', '=', 't2.nama_penerima')
                    ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
          })
          ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
          ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
          ->whereNull('t2.nama_penerima') // Filter nama_pengirim yang tidak ada di t2
          ->groupBy('t1.nama_penerima')
          ->get();

        // DataTables builder
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('idpjk', fn() => '1003780')
            ->addColumn('kode_nasabah', fn() => '1')
            ->addColumn('identitas_lain', fn() => '-')
            ->addColumn('npwp', fn() => '-')
            ->addColumn('local_id', fn() => '-')
            ->make(true);
    }

    return view('checking.newcustomer');

   
}

public function search_newcustomer_all(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          $send="ya";
          $tanggal = $request->reservation; // Misalnya "09/01/2024 - 09/30/2024"
          $dari = explode(' - ', $tanggal);
          $tanggal_dari = Carbon::createFromFormat('m/d/Y', trim($dari[0]))->format('Y-m-d');
          $tanggal_sampai = Carbon::createFromFormat('m/d/Y', trim($dari[1]))->format('Y-m-d');
          
          // Ambil tahun dari tanggal_sampai
          $tahun_sampai = Carbon::createFromFormat('Y-m-d', $tanggal_sampai)->year;
        
          $quer = DB::table('transaksi_send_pos as t1')
          ->select(
              DB::raw('MIN(t1.no) AS no'),
              't1.nama_pengirim',
              DB::raw('MIN(t1.no_referensi) AS no_referensi'),
              DB::raw('MIN(t1.tanggal) AS tanggal'),
              DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
              DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
              DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
              DB::raw('MIN(t1.kota_lahir_pengirim) AS kota_lahir_pengirim'),
              DB::raw('MIN(t1.alamat_pengirim) AS alamat_pengirim'),
              DB::raw('MIN(t1.telpon_pengirim) AS telpon_pengirim'),
              DB::raw('MIN(t1.negara_penerima) AS negara_penerima'),
              DB::raw('MIN(t1.kode_pos_domisili_pengirim) AS kode_pos_domisili_pengirim')
          )
          ->leftJoin('transaksi_send_pos as t2', function($join) use ($tanggal_dari) {
              $join->on('t1.nama_pengirim', '=', 't2.nama_pengirim')
                    ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
          })
          ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
          ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
          ->whereNull('t2.nama_pengirim') // Filter nama_pengirim yang tidak ada di t2
          ->groupBy('t1.nama_pengirim')
          ->get();                                

               
 $query  = DB::table('transaksi_valid as t1')
          ->select(
              DB::raw('MIN(t1.no) AS no'),
              't1.nama_penerima',
              DB::raw('MIN(t1.no_referensi) AS no_referensi'),
              DB::raw('MIN(t1.tanggal) AS tanggal'),
              DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
              DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
              DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
              DB::raw('MIN(t1.kota_kelahiran) AS kota_kelahiran'),
              DB::raw('MIN(t1.alamat_penerima) AS alamat_penerima'),
              DB::raw('MIN(t1.telepon_penerima) AS telepon_penerima'),
              DB::raw('MIN(t1.negara_pengirim) AS negara_pengirim'),
              DB::raw('MIN(t1.kode_pos_penerima) AS kode_pos_penerima')
          )
          ->leftJoin('transaksi_valid as t2', function($join) use ($tanggal_dari) {
              $join->on('t1.nama_penerima', '=', 't2.nama_penerima')
                    ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
          })
          ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
          ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
          ->whereNull('t2.nama_penerima') // Filter nama_pengirim yang tidak ada di t2
          ->groupBy('t1.nama_penerima')
          ->get();
          return view('checking.newcustomerall',[
            'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari'=>$tanggal_dari,'data'=>$quer,'datarcv'=>$query, 'send'=>$send
          ]);
          

        }

        return redirect('login')->with('You dont have access');
      }

/*
public function search_newcustomer(Request $request)
{
    if(Auth::check()){
          $send="ya";
          $tanggal = $request->reservation; // Misalnya "09/01/2024 - 09/30/2024"
          $dari = explode(' - ', $tanggal);
          $tanggal_dari = Carbon::createFromFormat('m/d/Y', trim($dari[0]))->format('Y-m-d');
          $tanggal_sampai = Carbon::createFromFormat('m/d/Y', trim($dari[1]))->format('Y-m-d');
          
          // Ambil tahun dari tanggal_sampai
          $tahun_sampai = Carbon::createFromFormat('Y-m-d', $tanggal_sampai)->year;
        
         $quer = DB::table('transaksi_valid as t1')
          ->select(
              DB::raw('MIN(t1.no) AS no'),
              't1.nama_penerima',
              DB::raw('MIN(t1.no_referensi) AS no_referensi'),
              DB::raw('MIN(t1.tanggal) AS tanggal'),
              DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
              DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
              DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
              DB::raw('MIN(t1.kota_kelahiran) AS kota_kelahiran'),
              DB::raw('MIN(t1.alamat_penerima) AS alamat_penerima'),
              DB::raw('MIN(t1.telepon_penerima) AS telepon_penerima'),
              DB::raw('MIN(t1.negara_pengirim) AS negara_pengirim'),
              DB::raw('MIN(t1.kode_pos_penerima) AS kode_pos_penerima')
          )
          ->leftJoin('transaksi_send_pos as t2', function($join) use ($tanggal_dari) {
              $join->on('t1.nama_penerima', '=', 't2.nama_penerima')
                    ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
          })
          ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
          ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
          ->whereNull('t2.nama_penerima') // Filter nama_pengirim yang tidak ada di t2
          ->groupBy('t1.nama_penerima')
          ->get();


          return view('checking.newcustomer_new',[
            'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari'=>$tanggal_dari,'data'=>$quer
          ]);


        }

        return redirect('login')->with('You dont have access');
}
*/

      public function search_newcustomer_send(Request $request){ 
        //  cek berhasil login 
        if(Auth::check()){
          $send="ya";
          $tanggal = $request->reservation; // Misalnya "09/01/2024 - 09/30/2024"
          $dari = explode(' - ', $tanggal);
          $tanggal_dari = Carbon::createFromFormat('m/d/Y', trim($dari[0]))->format('Y-m-d');
          $tanggal_sampai = Carbon::createFromFormat('m/d/Y', trim($dari[1]))->format('Y-m-d');
          
          // Ambil tahun dari tanggal_sampai
          $tahun_sampai = Carbon::createFromFormat('Y-m-d', $tanggal_sampai)->year;
        
          $quer = DB::table('transaksi_send_pos as t1')
          ->select(
              DB::raw('MIN(t1.no) AS no'),
              't1.nama_pengirim',
              DB::raw('MIN(t1.no_referensi) AS no_referensi'),
              DB::raw('MIN(t1.tanggal) AS tanggal'),
              DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
              DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
              DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
              DB::raw('MIN(t1.kota_lahir_pengirim) AS kota_lahir_pengirim'),
              DB::raw('MIN(t1.alamat_pengirim) AS alamat_pengirim'),
              DB::raw('MIN(t1.telpon_pengirim) AS telpon_pengirim'),
              DB::raw('MIN(t1.negara_penerima) AS negara_penerima'),
              DB::raw('MIN(t1.kode_pos_domisili_pengirim) AS kode_pos_domisili_pengirim')
          )
          ->leftJoin('transaksi_send_pos as t2', function($join) use ($tanggal_dari) {
              $join->on('t1.nama_pengirim', '=', 't2.nama_pengirim')
                    ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
          })
          ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
          ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
          ->whereNull('t2.nama_pengirim') // Filter nama_pengirim yang tidak ada di t2
          ->groupBy('t1.nama_pengirim')
          ->get();                                

               

          return view('checking.newcustomersend',[
            'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari'=>$tanggal_dari,'data'=>$quer,'send'=>$send
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

      public function cari_screening_operasional(Request $request){ 
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
                 $data = DB::table('sanction_list')
    ->select(
        'id',
        'nama',
        'alias',
        DB::raw('COALESCE(tanggal_lahir, \'\') as tanggal_lahir'),
        'negara',
        'no_ktp',
        'nohp',
        'keterangan',
        'alamat',
        'type'
    )
    ->orderBy('id', 'desc')
    ->get();

                  return Datatables::of($data)
                  ->addIndexColumn()
                  ->addColumn('action', function($row){
                      //$actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                      //return $actionBtn;
                  })
                  ->rawColumns(['action'])
                  ->make(true);
                }
               return view('sanctions.sanction', ['tambah' => null]);
              

          }

          return redirect('login')->with('You dont have access');
        }

        public function sanction_operasional(Request $request)
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
                 $data = DB::table('sanction_list')
    ->select(
        'id',
        'nama',
        'alias',
        DB::raw('COALESCE(tanggal_lahir, \'\') as tanggal_lahir'),
        'negara',
        'no_ktp',
        'nohp',
        'keterangan',
        'alamat',
        'type'
    )
    ->orderBy('id', 'desc')
    ->get();

                  return Datatables::of($data)
                  ->addIndexColumn()
                  ->addColumn('action', function($row){
                      //$actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                      //return $actionBtn;
                  })
                  ->rawColumns(['action'])
                  ->make(true);
                }
               return view('sanctions.sanction_operasional', ['tambah' => null]);
              

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
    public function tambah_sanction_operasional(Request $request)
        { 
          if(Auth::check()){
            $tambah = "ya";
            return view('sanctions.sanction_operasional', [
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
          $cekId = DB::table('sanction_list')->orderBy('id','desc')->first();
          if($cekId==null){
            $idNya = 1;
          }else{
            $idNya = $cekId->id+1;
          }
          $qur = DB::table('sanction_list')->insertGetId([
            'id' =>  $idNya,
            'nama' => $nama,
            'alias' => $alias,
            'tanggal_lahir' => $tgl_lahir,
            'alamat' => $alamat,
            'negara' => $asalnegara,
            'no_ktp' => $nomor_identitas,
            'nohp' => $telepon_penerima,
            'keterangan' => $keterangan,
            'type' => $kategori,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
        if($qur){
          return redirect('sanction')->with('success','You have successfully add data');
        }
        
          
      }

      return redirect('login')->with('You dont have access');
    }

    public function act_tambah_sanction_operasional(Request $request)
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
          $cekId = DB::table('sanction_list')->orderBy('id','desc')->first();
          if($cekId==null){
            $idNya = 1;
          }else{
            $idNya = $cekId->id+1;
          }
          $qur = DB::table('sanction_list')->insertGetId([
            'id' =>  $idNya,
            'nama' => $nama,
            'alias' => $alias,
            'tanggal_lahir' => $tgl_lahir,
            'alamat' => $alamat,
            'negara' => $asalnegara,
            'no_ktp' => $nomor_identitas,
            'nohp' => $telepon_penerima,
            'keterangan' => $keterangan,
            'type' => $kategori,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
        if($qur){
          return redirect('sanction_operasional')->with('success','You have successfully add data');
        }
        
          
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

