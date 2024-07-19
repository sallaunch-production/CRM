<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Saldo;
use App\Models\SaldoHistory;
use App\Models\TransaksiSendPos;
use App\Models\TransaksiValid;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard ()
    {
        if(Auth::check()){
            $email_s = Auth::user()->email;
            $exp_pass_cek = DB::table('users')->where('email','=',$email_s)->first();     
            $date1 = date_create($exp_pass_cek->last_update); 
            $date2 = date_create(date("Y-m-d H:i:s")); 
            $date3 = $exp_pass_cek->last_update;
            $diff = date_diff($date1, $date2); 
            $selisih = $diff->format('Selisih %R%a hari'); 
            $selisihhari= $diff->format('%R%a'); 
            $now = Carbon::now();
            $formatted_dt1=Carbon::parse($now);
            $formatted_dt2=Carbon::parse($date3);

            $DeferenceInDays =$diff->format('%a');
           
            if ($diff->format('%a') > 90){
                $sisa=90-$diff->format('%a');
                //return redirect()->back()->with('alert','hello');
                return redirect('settings')->with('success','Your password has expired and must be changed'.$diff->format('%a'));
                
            } else if ($diff->format('%a') > 76){
                $sisa=90-$diff->format('%a');
                if($sisa > 1){
                   return redirect('settings')->with('success','Your password will expired in '.$sisa.' days.');
                }
                else{
                   return redirect('settings')->with('success','Your password has expired and must be changed');
                 }
            }


            return view('dashboard.home');
        }

        return redirect('login')->with('You dont have access');
    }

    public function settings_user()
    {
        if(Auth::check()){

            return view('ubah-password');
        }
        return redirect('login')->with('You dont have access');
    }


    public function ubah_password(Request $request)
    {
        if(Auth::check()){
            $current_password = $request->input('old_password');
            $new_pass=$request->new_password;
            $conf_pass=$request->conf_password;
            $username=Auth::user()->email;
            $cekpass=DB::table('users')->where('email','=',$username)->first();
            //$qur=DB::table('users')->where('password','=',$old_pass)->count();
            if (!Hash::check($current_password, Auth::user()->password)) {
                return redirect()->back()->with('error', 'Old password are wrong!');
            }elseif($new_pass<>$conf_pass){
                return redirect()->back()->with('error', 'Confimr password not same!');

            }elseif(strlen($new_pass)<8){
                return redirect()->back()->with('error', 'Minimum password lenght 8 character');
            }else{
                $now = Carbon::now();
                $qur=DB::table('users')->where('email','=',$username)->update(['password' =>  Hash::make($request->new_password),'last_update'=>$now]);
                if(Auth::user()->level=="compliance"){
                    return redirect('settings_user')->with('success','Success update password');
                }elseif(Auth::user()->level=="operasional"){
                    return redirect('settings')->with('success','Success update password');
                }
                
            }

        }
        return redirect('login')->with('You dont have access');
    }

    
}
