<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;
use Carbon\Carbon;
use Create;
use PragmaRX\Google2FAQRCode\Google2FA;


class AuthController extends Controller
{
    
    protected $redirectTo;

    //
    public function index(){
        return view('auth.login');
     }

     public function register(){ 
        return view('auth.register'); 
     }

     public function proses_login(Request $request){
        $user_email = $request->email;
        $password = $request->password;
        $stmt= DB::table('user_block')->where('username','=',$user_email)->get();
        $key = "login.".$request->ip();
        $max = 3; // attempt
        $decay = 60; // seconds
       
        if(RateLimiter::tooManyAttempts($key,$max)){
            if(count($stmt)==0) {
              $cek = DB::table('user_block')->insertGetId([
                 'username' => $request->email,
                 'url' => url()->current(),
                 'action' => "Login",
                 'tanggal' => Carbon::now()->toDateTimeString(),
                 'ip_address' => $_SERVER['REMOTE_ADDR'],
                 'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                 'created_at' => Carbon::now()->toDateTimeString(),
                 'updated_at' =>Carbon::now()->toDateTimeString(),
             ]);

             $audit = DB::table('audit_trail')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "User block",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' =>Carbon::now()->toDateTimeString(),
            ]);
            }
             
            return redirect()->back()->with('status', 'Username was blocked!');
         } else {
             RateLimiter::hit($key,$decay);
         }

         $recaptcha_response = $request->input('g-recaptcha-response');

        if (is_null($recaptcha_response)) {
            return redirect()->back()->with('status', 'Please Complete the Recaptcha to proceed');
        }

       
        Session::put('user_email', $user_email);
        Session::put('user_password', $password);

        if(count($stmt)>0){
            return redirect()->back()->with('status', 'Maaf, User anda terblokir.');
        }else{
            $cek_email = User::where('email','=',$user_email)->get();
            if(count($cek_email)>0){
                if (Hash::check($password, $cek_email[0]['password'])){
                    // Success
                    $cek_user =  DB::table('users')->where('email','=',$user_email)->get();
                    foreach($cek_user as $cek_users)
                    {
                        $namanya=$cek_users->name;
                        Session::put('user_name', $namanya);
                    }
                    $cek_google =  DB::table('auth_google')->where('username','=',$cek_email[0]['email'] ?? '')->get();
                    if(count($cek_google)>0){
                        return redirect('validation')->with('success','Silahkan masukan code dari perangakat seluler anda.');
                    }else{
                        return redirect('register_barcode')->with('success','Silahkan masukan code dari perangakat seluler anda.');
                    }
                    
               }else{
                return redirect()->back()->with('status', 'Username or password are wrong!');
               }
            }else{
                    
                return redirect()->back()->with('status', 'Username or password are wrong!');
            }
        }
      }

      
      
      public function verify(Request $request){ 
        //  cek berhasil login 
            $email=Session::get('user_email'); 
            $password=Session::get('user_password'); 
            $satu=$request->satu;
            $dua=$request->dua;
            $tiga=$request->tiga;
            $empat=$request->empat;
            $lima=$request->lima;
            $enam=$request->enam;
            $request_code=$satu.$dua.$tiga.$empat.$lima.$enam;
            $user = DB::table('users')->where('email','=',$email)->get();
            foreach($user as $users){
                $nama=$users->name;
                $level=$users->level;
            }
            $cek_code = DB::table('auth_google')->where('username','=',$email)->get();
            foreach($cek_code as $cek_codes){
                $codenya=$cek_codes->code;
            }
            $google2fa = new Google2FA();
            $otp_secret = $codenya;
            $valid =$google2fa->verifyKey($otp_secret, $request_code);
          
            $credentials =  ['email'=>$email,'password'=>$password];
           
            //$valid = $google2fa->verifyKey($request->code,$secret);
            if(Auth::attempt($credentials) && $valid){
                session()->forget('user_password');
                $qur = DB::table('audit_trail')->insertGetId([
                    'username' => Auth::user()->email,
                    'url' => url()->current(),
                    'action' => "Login",
                    'tanggal' => Carbon::now()->toDateTimeString(),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' =>Carbon::now()->toDateTimeString(),
                ]);
                // 2FA code is valid, proceed with login
               if($level=="compliance"){
                    return redirect()->intended('dashboard')->with('success','Successfully Login.');
               }elseif($level=="operasional"){
                    return redirect()->intended('dashboard_operasional')->with('success','Successfully Login.');
               }
                 
            } else {
                // Invalid 2FA code, redirect back with an error message
                return redirect()->back()->with('login_error', 'Code is wrong!');
            }
      }

      public function validation(){ 
        //  cek berhasil login 
           return view('auth.validation'); 
      }

      public function dashboard(){ 
        //  cek berhasil login 
        if(Auth::check()){
            return view('home');

        }

        return redirect('login')->with('You dont have access');
      }

      public function proses_register(Request $request){
        $credentials =  $request->only('name','email','password','password_confirmation');
       //validasi menggunakan Illuminate\Support\Facades\Validator
        //isi field validasi dan rules nya yaitu wajib di isi
        
        $cekUser =  User::where('email','=', $request->email)->count();
        
         if($request->password!=$request->password_confirmation){
            //kembali ke halaman login & tampilkan error pada setiap inputnya
            return back()->withErrors(['register_error'=>'Password not same!']);
        }else if($cekUser>=1){
            return back()->withErrors(['register_error'=>'Email duplicate.']);
        }

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'level' => 'admin'
        ]);

        // tambahkan field level dan kita isi dengan admin
        $request['level'] = 'admin';
        // panggil model User dan jalankan fungsi ORM create untuk melakukan insert semua data
        //User::create($request->all());
        if($user){
            return redirect('dashboard')->with('success','You have successfully register');
        }
       

       }

       public function logout(){
        $qur = DB::table('audit_trail')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Logout",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
        //  clear session dan memberitahu auth dengan status logout
            Session::flush();
            Auth::logout();
            return Redirect('login');
        }


        public function register_barcode(Request $request)
        {
           
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $emailUser = Session::get('user_email');
        $passUser = Session::get('user_password');
        $nameUser = Session::get('user_name');
        $idmax = DB::table('auth_google')->orderBy('id','desc')->first();
        $idnya=(int)$idmax->id +1;
        $cek_barcode= DB::table('auth_google')->where('username','=',$emailUser)->count();
        if($cek_barcode<=0){
        $barcode = DB::table('auth_google')->insertGetId([
            'id'=>$idnya,
            'username' => $emailUser,
            'code' => $secret,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);

        $audit = DB::table('audit_trail')->insertGetId([
            'username' => $emailUser,
            'url' => url()->current(),
            'action' => "Scan barcode login",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
    }

        $QR_Image = $google2fa->getQRCodeInline(
            "CRM Arsema",
            $emailUser,
            $secret
        );
        Session::put('2fa_secret', $secret);
            // Passing the QR barcode image to view
            return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $secret]);
        }

        public function erro_page()
        {
          return view('404');
        }
         
        // return $next($request);
   
}