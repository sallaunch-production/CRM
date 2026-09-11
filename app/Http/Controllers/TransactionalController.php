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
use App\Support\PosDatatableSearch;
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
            if ($request->filled('search.value')) {
              PosDatatableSearch::apply($query, $request->input('search.value'), 'bsubanyak');
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
            if ($request->filled('search.value')) {
              PosDatatableSearch::apply($query, $request->input('search.value'), 'bsubanyak');
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
            if ($request->filled('search.value')) {
              PosDatatableSearch::apply($query, $request->input('search.value'), 'bsubanyak');
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
          $query = TransaksiSendPos::orderBy('tanggal','desc'); 
          if ($request->filled('search.value')) {
                  PosDatatableSearch::apply($query, $request->input('search.value'), 'amount');
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
        if ($request->filled('search.value')) {
                  PosDatatableSearch::apply($query, $request->input('search.value'), 'amount');
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
                if ($request->filled('search.value')) {
                  PosDatatableSearch::apply($query, $request->input('search.value'), 'amount');
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
              if ($request->filled('search.value')) {
                  PosDatatableSearch::apply($query, $request->input('search.value'), 'amount');
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