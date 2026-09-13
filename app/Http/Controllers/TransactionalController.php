<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSendPos;
use App\Models\TransaksiValid;
use App\Support\PosDatatableResponse;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionalController extends Controller
{
    public function transaksi_receive_pos(Request $request)
    {
        return $this->receivePos($request);
    }

    public function transaksi_receive_pos_operasional(Request $request)
    {
        return $this->receivePos($request);
    }

    public function transaksi_send_pos(Request $request)
    {
        return $this->sendPos($request);
    }

    public function transaksi_send_pos_operasional(Request $request)
    {
        return $this->sendPos($request);
    }

    protected function receivePos(Request $request)
    {
        if (! Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        if ($request->ajax()) {
            return response()->json(
                PosDatatableResponse::fromRequest($request, TransaksiValid::class, 'bsubanyak')
            );
        }

        $this->auditTrail('Transaksi Receive Pos');

        return view('reporting.transaksi');
    }

    protected function sendPos(Request $request)
    {
        if (! Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        if ($request->ajax()) {
            return response()->json(
                PosDatatableResponse::fromRequest($request, TransaksiSendPos::class, 'amount')
            );
        }

        $this->auditTrail('Transaksi Send Pos');

        return view('reporting.transaksi_send_pos');
    }

    protected function auditTrail(string $action): void
    {
        DB::table('audit_trail_crm')->insert([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => $action,
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
