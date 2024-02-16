<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\Datatables;
use App\Models\TransaksiSendPos;
use DB;

class TransactionalController extends Controller
{

    public function transaksi(Request $request)
    { 
        //  cek berhasil login 
        if(Auth::check()){
          if ($request->ajax()) {
           $query = DB::table('transaksi_valid')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                /*->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })*/
                //->rawColumns(['action'])
                ->make(true);
        }
          
            return view('reporting.transaksi');

        }

        return redirect('login')->with('You dont have access');
      }


      public function transaksi_send_pos(){ 
        //  cek berhasil login 
        if(Auth::check()){
            $hariini = date("Y-m-d H:i:s");
            $query = TransaksiSendPos::orderBy('tanggal','desc')->get();
            return view('reporting.transaksi_send_pos', [
                'hariini' => $hariini,'query'=>$query
             ]);

        }

        return redirect('login')->with('You dont have access');
      }

}