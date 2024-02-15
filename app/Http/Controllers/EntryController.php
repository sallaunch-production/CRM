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

class EntryController extends Controller
{
    public function saldo(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $transaction=DB::table('saldo')->orderBy('tanggal','desc')->get();
                return view('entry.saldo', [
                    'saldo' => $transaction
             ]);


        }

        return redirect('login')->with('You dont have access');
      }


      public function edit_saldo(Request $request)
      {
          $no=$request->no;
          //lakukan delete pada data customer sesuai parameter id
          $edit = DB::table('saldo')->where('no',$no)->get();
          //$id_s = $edit[0]['id'];
          $result= json_decode($edit, true);
          $nos = $result[0]['no'] ??'';
          $nominals = $result[0]['nominal'];   
          $tanggals = $result[0]['tanggal'];
          $ips = $result[0]['ip'];   
          $keterangans = $result[0]['keterangan'];
            return view('entry.edit_saldo', [
                 'no' => $nos,'nominal'=>$nominals,'tanggal'=>$tanggals,'ip'=>$ips,'keterangan'=>$keterangans
            ]);
            return redirect('login')->with('You dont have access');
      }


      public function store_edit_saldo(Request $request){
        $hariini2=date('Y-m-d h:m:s');
        $username = Auth::user()->email;
        $name = Auth::user()->name;
        $ip_s = $request->getClientIp();
        $nominal = str_replace(',','',$request->nominal); 
        $saldo = Saldo::where('no',$request->no)->update([
            'nominal' => $nominal,
            'keterangan' => $request->keterangan,
            'tanggal' =>  $hariini2,
            'user' => $username,
            'updated_by' => $name,
            'ip' => $ip_s,
        ]);

        return redirect('saldo')
        ->with('success', 'Berhasil edit saldo.'); 
      }

      public function saldo_destroy(Request $request)
      {
          $no=$request->no;
          //lakukan delete pada data customer sesuai parameter id
          $hapus = DB::table('saldo')->where('no',$no)->delete();
  
          return redirect('saldo')->with('success','Successfully delete data');
      }
      
      public function add_saldo(Request $request)
      {
        $hariini2=date('Y-m-d h:m:s');
        $ip_s = $request->getClientIp();
        $idsal = Saldo::orderBy('no','desc')->get();
        $idnya = $idsal[0]['no']+1;
        $name = Auth::user()->name;
        $username = Auth::user()->email;
        $nominal = str_replace(',','',$request->nominal); 
        $saldo = Saldo::create([
            'no' => $idnya,
            'nominal' => $nominal,
            'keterangan' => $request->keterangan,
            'ip' => $ip_s,
            'updated_by' => $name,
            'user' => $username,
            'tanggal'=> $hariini2,
        ]);
        if($saldo){
            return redirect('saldo')->with('success','You have successfully add saldo');
        }
      }

      public function reject()
      {
        if(Auth::check()){
            $reject=DB::table('transaksi_reject')->orderBy('tgl','desc')->get();
                return view('entry.reject', [
                    'reject' => $reject
             ]);


        }

        return redirect('login')->with('You dont have access');

      }

      public function blocking()
      {
        if(Auth::check()){
            
            $country = DB::table('countryinfo')->orderBy('countryName','asc')->get();
           
            $blocking = DB::table('countryinfo')->join('negara_exception','negara_exception.negara','=','countryinfo.countryCode')->orderBy('negara_exception.id','asc')->get();
            
                return view('entry.blocking', [
                    'blocking' => $blocking, 'country'=> $country
             ]);


        }

        return redirect('login')->with('You dont have access');

      }

      
      public function negara_exception_add(Request $request)
      {
        $hariini2=date('Y-m-d h:m:s');
        $ids = NegaraException::orderBy('id','desc')->get();
        if(count($ids)<=0){
            $idnya = 1;
        }else{
            $idnya = $ids[0]['id']+1;
        }
       
        if($request->optradio == "kurang"){
            $nominal = str_replace(',','',$request->bsumin); 
            $saldo = NegaraException::create([
                'id' => $idnya,
                'negara' => $request->negara_kirim,
                'bsu' => $nominal,
                'tanggal' => $hariini2,
            ]);
            if($saldo){
                return redirect('blocking')->with('success','You have successfully add exception');
            }
        }else if($request->optradio == "lebih"){
            $nominal = str_replace(',','',$request->bsumin); 
            $saldo = NegaraException::create([
                'id' => $idnya,
                'negara' => $request->negara_kirim,
                'maksimum' => $nominal,
                'tanggal' => $hariini2,
            ]);
            if($saldo){
                return redirect('blocking')->with('success','You have successfully add exception');
            }
        }else if($request->optradio == "antara"){
            $nominal = str_replace(',','',$request->bsumin); 
            $saldo = NegaraException::create([
                'id' => $idnya,
                'negara' => $request->negara_kirim,
                'dari' => $nominal,
                'sampai' => $request->bsusampai,
                'tanggal' => $hariini2,
            ]);
            if($saldo){
                return redirect('blocking')->with('success','You have successfully add exception');
            }
        }
       
      }

      
      public function exception_destroy(Request $request)
      {
          $id = $request->id;
          //lakukan delete pada data customer sesuai parameter id
          $hapus = DB::table('negara_exception')->where('id',$id)->delete();
          return redirect('blocking')->with('success','Successfully delete data');
      }

}

