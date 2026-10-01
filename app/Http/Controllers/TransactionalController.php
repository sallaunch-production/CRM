<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSendPos;
use App\Models\TransaksiValid;
use App\Support\CrmTransactionDetail;
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

    public function transaksi_receive_pos_detail($no)
    {
        return $this->receivePosDetail($no, false);
    }

    public function transaksi_receive_pos_detail_operasional($no)
    {
        return $this->receivePosDetail($no, true);
    }

    public function transaksi_send_pos_detail($no)
    {
        return $this->sendPosDetail($no, false);
    }

    public function transaksi_send_pos_detail_operasional($no)
    {
        return $this->sendPosDetail($no, true);
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

    protected function receivePosDetail($no, bool $isOps)
    {
        if (! Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        $tx = TransaksiValid::query()->where('no', $no)->firstOrFail();
        $backRoute = $isOps ? 'transaksi_receive_pos_operasional' : 'transaksi_receive_pos';

        $this->auditTrail('Transaksi Receive Pos Detail');

        return view('reporting.transaksi_pos_detail', [
            'channel' => 'receive',
            'backRoute' => $backRoute,
            'pageTitle' => __('crm.tx_receive_detail_title'),
            'pageSub' => __('crm.tx_receive_detail_sub'),
            'heroName' => $tx->nama_penerima ?: ($tx->no_referensi ?: '—'),
            'heroMeta' => $tx->no_referensi ?: '',
            'highlights' => CrmTransactionDetail::receiveHighlights($tx),
            'sections' => CrmTransactionDetail::receiveSections($tx),
        ]);
    }

    protected function sendPosDetail($no, bool $isOps)
    {
        if (! Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        $tx = TransaksiSendPos::query()->where('no', $no)->firstOrFail();
        $backRoute = $isOps ? 'transaksi_send_pos_operasional' : 'transaksi_send_pos';

        $this->auditTrail('Transaksi Send Pos Detail');

        return view('reporting.transaksi_pos_detail', [
            'channel' => 'send',
            'backRoute' => $backRoute,
            'pageTitle' => __('crm.tx_send_detail_title'),
            'pageSub' => __('crm.tx_send_detail_sub'),
            'heroName' => $tx->nama_pengirim ?: ($tx->no_referensi ?: '—'),
            'heroMeta' => $tx->no_referensi ?: '',
            'highlights' => CrmTransactionDetail::sendHighlights($tx),
            'sections' => CrmTransactionDetail::sendSections($tx),
        ]);
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
