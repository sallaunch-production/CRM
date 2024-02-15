<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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

        // isi credential hanya berupa username dan password
        $credentials =  $request->only('email','password');
        //validasi menggunakan Illuminate\Support\Facades\Validator
        //isi field validasi dan rules nya yaitu wajib di isi
        $validate = Validator::make($credentials,[
            'email'=>'required|email',
            'password'=>'required',
            
        ]);
        $recaptcha_response = $request->input('g-recaptcha-response');

        if (is_null($recaptcha_response)) {
            return redirect()->back()->with('status', 'Please Complete the Recaptcha to proceed');
        }

        if($validate->fails()){
            //kembali ke halaman login & tampilkan error pada setiap inputnya
            return back()->withErrors($validate)->withInput();
        }
        

        // verifikasi data user pada kolom email dan password sesuai atau belum
        if(Auth::attempt($credentials)){
            //jika sesuai maka jalankan fungsi dashboard
            return redirect()->intended('dashboard')->with('success','Successfully Login');
        }
        //return back()->withErrors($validate)->withInput();
        return redirect()->back()->with('status', 'Username or password are wrong!');
        //return redirect('login')->withInput()->withErrors(['login_error'=>'Username or password are wrong!']);
        // jika terdapat field yang kosong
      
        // kembali ke halaman login dan tampilkan pesan error pada login_error
       
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
        //  clear session dan memberitahu auth dengan status logout
            Session::flush();
            Auth::logout();
            return Redirect('login');
        }

        public function erro_page()
        {
          return view('404');
        }
         
        // return $next($request);
   
}