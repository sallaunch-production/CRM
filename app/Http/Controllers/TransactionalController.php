<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransaksiSendPos;
use App\Models\TransaksiValid;
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
            $query  = TransaksiValid::orderBy('tanggal','desc'); 
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
                    ->orWhere('id_pos', 'like', "%{$searchTerm}%")
                    ->orWhere('tanggal', 'like', "%{$searchTerm}%");
              });
            }

            $recordsTotal = TransaksiValid::count();
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
            $query  = TransaksiValid::orderBy('tanggal','desc'); 
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
                    ->orWhere('id_pos', 'like', "%{$searchTerm}%")
                    ->orWhere('tanggal', 'like', "%{$searchTerm}%");
              });
            }

            $recordsTotal = TransaksiValid::count();
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
            }if ($request->ajax()) {
            $query  = TransaksiValid::orderBy('tanggal','desc'); 
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
                    ->orWhere('id_pos', 'like', "%{$searchTerm}%")
                    ->orWhere('tanggal', 'like', "%{$searchTerm}%");
              });
            }

            $recordsTotal = TransaksiValid::count();
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
                ->rawColumns(['action','action'])
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
                $query = TransaksiSendPos::orderBy('tanggal','desc'); 
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
                      ->orWhere('tanggal', 'like', "%{$searchTerm}%");
                });
              }
              $recordsTotal = TransaksiSendPos::count();
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
              $query  = TransaksiSendPos::orderBy('tanggal','desc'); 
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
                  ->orWhere('tanggal', 'like', "%{$searchTerm}%");
                });
              }
  
              $recordsTotal = TransaksiSendPos::count();
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
               return view('reporting.transaksi_send_pos');
            

        }

        return redirect('login')->with('You dont have access');
      }




}