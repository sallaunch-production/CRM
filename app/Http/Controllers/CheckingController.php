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
    public function transaksi_anomali(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $thismonth=date("Y-m");
            $transaction=DB::table('transaksi_anomali')->where('tanggal','LIKE','%'.$thismonth.'%')->orderBy('no','desc')->get();
                return view('checking.transaksi_anomali', [
                    'transaction' => $transaction
             ]);


        }

        return redirect('login')->with('You dont have access');
      }

      public function anomali_destroy(Request $request)
      {
          $no=$request->no;
          //lakukan delete pada data customer sesuai parameter id
          $hapus = DB::table('transaksi_anomali')->where('no',$no)->delete();
  
          return redirect('transaksi_anomali')->with('success','Successfully delete data');
      }

      public function error_test(){ 
            //  cek berhasil login 
            if(Auth::check()){
                $hariini = date("Y-m-d H:i:s");
                return view('checking.error_test', [
                    'hariini' => $hariini
                ]);
            }

        return redirect('login')->with('You dont have access');
      }


      public function viewing(){ 
        //  cek berhasil login 
            if(Auth::check()){
                $tanggal_dari="";
                $hariini = date("Y-m-d H:i:s");
                return view('checking.viewing2', [
                    'hariini' => $hariini,' tanggal_dari'=> $tanggal_dari
                ]);
            }

        return redirect('login')->with('You dont have access');
      }


      public function request_setor(){ 
        //  cek berhasil login 
            if(Auth::check()){
                $hariini = date("Y-m-d H:i:s");
                return view('checking.request_setor2', [
                    'hariini' => $hariini
                ]);
            }

        return redirect('login')->with('You dont have access');
      }

      public function search(){ 
        //  cek berhasil login 
            if(Auth::check()){
                $cari="";
                $hariini = date("Y-m-d H:i:s");
                return view('checking.search', [
                    'hariini' => $hariini,'cari'=>$cari
                ]);
            }

        return redirect('login')->with('You dont have access');
      }

      public function cek_status(){ 
        //  cek berhasil login 
            if(Auth::check()){
                $hariini = date("Y-m-d H:i:s");
                return view('checking.cek-status', [
                    'hariini' => $hariini
                ]);
            }

        return redirect('login')->with('You dont have access');
      }

      public function search_print(Request $request)
      {

        if(Auth::check()){

            $cari = $request->cari;
            $hariini = date("Y-m-d H:i:s");
            $que_cari = TransaksiValid::where('no_referensi','LIKE','%'.$cari.'%')->orWhere('id_pos','LIKE','%'.$cari.'%')->orWhere('nama_penerima','LIKE','%'.$cari.'%')->orWhere('negara_pengirim','LIKE','%'.$cari.'%')->orderBy('tanggal','desc')->get();

            return view('checking.search', [
                'que_cari' => $que_cari,'cari'=>$cari
            ]);
        }

            return redirect('login')->with('You dont have access');
      }

      public function agent_copy(Request $request)
      {
          if(Auth::check()){
                  $ids = $request->no;
                  $no_ref = $request->no_referensi;
                  $query=TransaksiValid::where('no','=',$ids)->get();
                  $querya=DB::table('transaksi')->where('no_referensi','=',$no_ref)->get();
                  $negara_penerimas=$query[0]['negara_penerima'] ?? '';
                  $photoidcountrys=$query[0]['photoidcountry'] ?? '';
                  $photoidstates=$query[0]['photoidstate'] ?? '';
                  $querynegarapen=DB::table('countryinfo')->where('countryCode','=',$negara_penerimas)->get();
                  $querynegaras=DB::table('countryinfo')->where('countryCode','=',$photoidcountrys)->get();
                  $queryprovince=DB::table('stateprovinceinfo')->where('countryCode','=',$photoidcountrys)->where('stateProvinceCode','=',$photoidstates)->get();
                  
                  
                  return view('checking.agent-copy', [
                      'dataa'=>$querya,'data'=>$query,'datanegaras'=>$querynegaras,'dataprovince'=>$queryprovince
                  ]);
  
          }
          return redirect('login')->with('You dont have access');
      }
      

      public function search_viewing(Request $request)
      {
          if(Auth::check()){
            $no_referensi= $request->no_referensi;
            $user= Auth::user()->name;
            $hariini = date("Y-m-d H:i:s");
            $tglan= date('Y-m-d');
            $filename="logmg/viewing_request_setor_log.".date("Y-m-d").".txt";
			$waktu=date("Y-m-d H:i:s");
			$Request = "\n Viewing : $no_referensi \n Oleh : $user  \n Waktu : $waktu \n";

			if (is_writable($filename)) {

			// In our example we're opening $filename in append mode.
			// The file pointer is at the bottom of the file hence
			// that's where $somecontent will go when we fwrite() it.
			if (!$handle = fopen($filename, 'a')) {

				 exit;
			}

			// Write $somecontent to our opened file.
			if (fwrite($handle, $Request) === FALSE) {

				exit;
			}



			fclose($handle);

		} else {
			//echo "The file $filename is not writable";
		}
		
		//$handles=fopen($filename, 'a');
        //fwrite($handles, $Request);
        $query=DB::table('transaksi')->where('status','=','valid')->whereNull('proses')->where('mgisession','<>','')->where('tgl','<>',$tglan.' 00:00:00')->where('no_referensi','=',$no_referensi)->orderBy('tgl','desc')->get();


                return view('checking.viewing2', [
                    'hariini' => $hariini,'no_referensi'=>$no_referensi,'data_query'=>$query
                ]);

          }
          return redirect('login')->with('You dont have access');
        }
}

