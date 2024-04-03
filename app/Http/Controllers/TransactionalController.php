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
use Carbon\Carbon;

class TransactionalController extends Controller
{

    public function transaksi_receive_pos(Request $request)
    { 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Transaksi Receive Pos",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
          if ($request->ajax()) {
           $data = DB::table('transaksi_valid')->limit(100000)->orderBy('tanggal','desc')->get(); 
           return Datatables::of($data)
           ->addIndexColumn()
           ->addColumn('action', function($row){
               //$actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
               //return $actionBtn;
           })
           ->rawColumns(['action'])
           ->make(true);
          }
          return view('reporting.transaksi');
        }

        return redirect('login')->with('You dont have access');
      }

      public function transaksi_receive_pos_operasional(Request $request)
      { 
          //  cek berhasil login 
          if(Auth::check()){
            $qur = DB::table('audit_trail_crm')->insertGetId([
              'username' => Auth::user()->email,
              'url' => url()->current(),
              'action' => "Transaksi Receive Pos",
              'tanggal' => Carbon::now()->toDateTimeString(),
              'ip_address' => $_SERVER['REMOTE_ADDR'],
              'user_agent' => $_SERVER['HTTP_USER_AGENT'],
              'created_at' => Carbon::now()->toDateTimeString(),
              'updated_at' =>Carbon::now()->toDateTimeString(),
          ]);
            if ($request->ajax()) {
             $data = DB::table('transaksi_valid')->limit(10000)->orderBy('tanggal','desc')->get(); 
             return Datatables::of($data)
             ->addIndexColumn()
             ->addColumn('action', function($row){
                 //$actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                 //return $actionBtn;
             })
             ->rawColumns(['action'])
             ->make(true);
            }
            return view('reporting.transaksi');
          }
  
          return redirect('login')->with('You dont have access');
        }


      public function transaksi_send_pos(Request $request)
      { 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Transaksi Send Pos",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
             if ($request->ajax()) {
                $data = TransaksiSendPos::limit(100000)->orderBy('tanggal','desc')->get(); 
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    //$actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    //return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
               }
               return view('reporting.transaksi_send_pos');
            

        }

        return redirect('login')->with('You dont have access');
      }

      public function transaksi_send_pos_operasional(Request $request)
      { 
        //  cek berhasil login 
        if(Auth::check()){
          $qur = DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => "Transaksi Send Pos",
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' =>Carbon::now()->toDateTimeString(),
        ]);
             if ($request->ajax()) {
                $data = TransaksiSendPos::limit(100000)->orderBy('tanggal','desc')->get(); 
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    //$actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    //return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
               }
               return view('reporting.transaksi_send_pos');
            

        }

        return redirect('login')->with('You dont have access');
      }




}