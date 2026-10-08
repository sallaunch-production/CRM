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
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransaksiSend;
use App\Models\TransaksiSendPos;
use App\Models\TransaksiValid;
use App\Services\CustomerAnalyticsService;
use App\Services\NewCustomerQueryService;
use App\Support\CrmTransactionDetail;
use DB;
use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Support\Collection;

class CheckingController extends Controller
{
    public function nama()
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $saldo = DB::table('audit_trail')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu Customer",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            return view('checking.nama');


        }

        return redirect('login')->with('You dont have access');
    }

    public function cari_nama(Request $request)
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $cari = $request->proses;

            // Prefer start_date / end_date (Y-m-d); keep legacy reservation fallback.
            if ($request->filled('start_date') && $request->filled('end_date')) {
                try {
                    $start = Carbon::createFromFormat('Y-m-d', trim((string) $request->start_date))->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', trim((string) $request->end_date))->startOfDay();
                } catch (\Throwable $e) {
                    return redirect()->back()->with('error', __('crm.err_invalid_date'))->withInput();
                }

                if ($end->lt($start)) {
                    return redirect()->back()->with('error', __('crm.err_end_before_start'))->withInput();
                }

                $tanggal_dari = $start->toDateString();
                $tanggal_sampai = $end->toDateString();
                $periode_dari = $start->format('d-m-Y');
                $periode_sampai = $end->format('d-m-Y');
            } else {
                $tanggal = $request->reservation;
                $dari = explode('/', $tanggal);

                $periode_dari = $dari[1] . '-' . $dari[0] . '-' . $dari[2][0] . $dari[2][1] . $dari[2][2] . $dari[2][3];
                $periode_sampai = $dari[3] . '-' . $dari[2][7] . $dari[2][8] . '-' . $dari[4];
                $tanggal_dari = $dari[2][0] . $dari[2][1] . $dari[2][2] . $dari[2][3] . '-' . $dari[0] . '-' . $dari[1];
                $tanggal_sampai = $dari[4] . '-' . $dari[2][7] . $dari[2][8] . '-' . $dari[3];
            }

            $query = DB::table('transaksi_valid')->where('tanggal', '>=', $tanggal_dari . ' 00:00:00')->where('tanggal', '<=', $tanggal_sampai . ' 23:59:59')->groupBy('nama_penerima', 'no')->orderBy('nama_penerima', 'asc')->get();

            return view('checking.nama', [
                'query' => $query,
                'cari' => $cari,
                'periode_dari' => $periode_dari,
                'periode_sampai' => $periode_sampai,
                'start_date' => $tanggal_dari,
                'end_date' => $tanggal_sampai,
            ]);


        }

        return redirect('login')->with('You dont have access');
    }

    public function profil()
    {
        //  audit internal 
        if (Auth::check()) {
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu Profile",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            return view('checking.profil');


        }

        return redirect('login')->with('You dont have access');
    }

    public function search_profil(Request $request)
    {
        if (Auth::check()) {

            $nama = trim((string) $request->nama);

            $allowed = [
                'nama',
                'telepon_penerima',
                'tanggal_lahir',
                'nomor_identitas'
            ];

            $berdasarkan = in_array($request->berdasarkan, $allowed, true)
                ? $request->berdasarkan
                : 'nama';

            // Tabel profil punya duplikat orang yang sama (beda id, isi hampir identik).
            // Listing di-unique: utamakan NIK; jika NIK kosong pakai nama+telepon+tgl lahir.
            // DISTINCT ON (PostgreSQL) ambil 1 baris per orang (id terbaru).
            $uniqueKey = "COALESCE(
                    NULLIF(TRIM(nomor_identitas), ''),
                    CONCAT(
                        COALESCE(TRIM(nama), ''), '|',
                        COALESCE(TRIM(telepon_penerima), ''), '|',
                        COALESCE(TRIM(tanggal_lahir), '')
                    )
                )";

            $dedup = DB::table('profil')
                ->leftJoin('users', function ($join) {
                    $join->on('users.id', '=', 'profil.lapor_updated_by');
                })
                ->selectRaw("
                    DISTINCT ON ({$uniqueKey}) 
                    profil.*,
                    users.name as lapor_updated_by_name
                    ")

                ->where($berdasarkan, 'ILIKE', '%' . $nama . '%')
                ->orderByRaw($uniqueKey)
                ->orderByDesc('id');

            $query = DB::query()
                ->fromSub($dedup, 'profil_unique')
                ->orderByDesc('nama')
                ->get();

            return view('checking.profil', [
                'dat' => $query,
                'nama' => $nama
            ]);


        }

        return redirect('login')->with('You dont have access');
    }

    public function update_profil(Request $request)
    {
        if (Auth::check()) {
            $validated = $request->validate([
                'id' => ['required', 'integer'],
                'lapor' => ['required', 'string', 'in:LTKM,Monthly Fraud,Sanction'],
                'remark' => ['nullable', 'string', 'max:255'],
            ], [
                'lapor.required' => __('crm.err_lapor_required'),
                'lapor.in' => __('crm.err_lapor_invalid'),
            ]);

            $remark = trim((string) ($validated['remark'] ?? ''));

            DB::table('profil')
                ->where('id', $validated['id'])
                ->update([
                    'lapor' => $validated['lapor'],
                    'remark' => $remark !== '' ? $remark : null,

                    'lapor_updated_at' => Carbon::now(),
                    'lapor_updated_by' => Auth::id(),
                ]);

            return redirect()->back()->with(
                'success',
                __('crm.msg_profile_updated')
            );
        }

        return redirect('login')->with('You dont have access');
    }


    public function detail_transaksi(Request $request)
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $detail = "ya";
            $nama = $request->nama;
            $tanggal_lahir = $request->tanggal_lahir;
            $nomor_identitas = trim((string) $request->nomor_identitas);
            // nama dinormalisasi: rapikan spasi ganda + trim (format nama antar tabel tidak konsisten)
            $namaNorm = preg_replace('/\s+/', ' ', trim((string) $nama));

            // Pencocokan orang lintas tabel: utamakan NIK (format konsisten & hampir selalu terisi),
            // fallback ke nama yang dinormalisasi bila NIK kosong. tanggal_lahir TIDAK dipakai karena
            // formatnya berbeda-beda antar tabel (MM/DD/YYYY vs YYYY-MM-DD vs DDMMYYYY) sehingga exact match gagal.
            $applyMatch = function ($query, $nikCol, $namaCol) use ($nomor_identitas, $namaNorm) {
                if ($nomor_identitas !== '') {
                    $query->whereRaw("trim($nikCol) = ?", [$nomor_identitas]);
                } else {
                    $query->whereRaw("regexp_replace(btrim($namaCol), '\\s+', ' ', 'g') = ?", [$namaNorm]);
                }
                return $query;
            };

            // Tanpa JOIN ke tabel 'transaksi': join lama (INNER) membuang baris karena banyak
            // no_referensi tidak ada di 'transaksi' -> Receive Pos kosong. Tabel 'transaksi' juga
            // tidak ber-index no_referensi (berat). Kolom bsu & tgl_valid diambil dari transaksi_valid.
            $query = DB::table('transaksi_valid')
                ->where('transaksi_valid.deltawork', 'ya')
                ->whereNotNull('transaksi_valid.id_transaksi');
            $applyMatch($query, 'transaksi_valid.nomor_identitas', 'transaksi_valid.nama_penerima');
            $query = $query->select(
                'transaksi_valid.*',
                DB::raw("replace(transaksi_valid.bsubanyak, ',', '') as bsu"),
                DB::raw('transaksi_valid.tanggal as tgl_valid')
            )->get();

            $querySendPos = DB::table('transaksi_send_pos');
            $applyMatch($querySendPos, 'transaksi_send_pos.nomor_identitas', 'transaksi_send_pos.nama_pengirim');
            $querySendPos = $querySendPos->get();

            $querySend = DB::table('transaksi_send');
            $applyMatch($querySend, 'transaksi_send.nomor_identitas', 'transaksi_send.nama_pengirim');
            $querySend = $querySend->get();

            $queryReceive = DB::table('transaksi_receive');
            $applyMatch($queryReceive, 'transaksi_receive.nomor_identitas', 'transaksi_receive.nama_penerima');
            $queryReceive = $queryReceive->get();

            $queryReceiveClient = DB::table('transaksi_receive_clients');
            $applyMatch($queryReceiveClient, 'transaksi_receive_clients.nomor_identitas', 'transaksi_receive_clients.nama_penerima');
            $queryReceiveClient = $queryReceiveClient->get();

            $querySendClient = DB::table('transaksi_send_clients');
            $applyMatch($querySendClient, 'transaksi_send_clients.nomor_identitas', 'transaksi_send_clients.nama_pengirim');
            $querySendClient = $querySendClient->get();

            return view('checking.profil', [
                'dat' => $query,
                'nama' => $nama,
                'detail' => $detail,
                'querySendPos' => $querySendPos,
                'querySend' => $querySend,
                'queryReceive' => $queryReceive,
                'queryReceiveClient' => $queryReceiveClient,
                'querySendClient' => $querySendClient,
                'profilListing' => [
                    'nama' => $nama,
                    'alamat_penerima' => trim((string) $request->alamat_penerima),
                    'telepon_penerima' => trim((string) $request->telepon_penerima),
                    'tanggal_lahir' => trim((string) $tanggal_lahir),
                    'nomor_identitas' => $nomor_identitas,
                ],
            ]);


        }

        return redirect('login')->with('You dont have access');
    }

    public function sales()
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu Sales",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            return view('checking.sales');
        }

        return redirect('login')->with('You dont have access');
    }


    public function search_sales(Request $request)
    {
        //  cek berhasil login 
        if (Auth::check()) {
            try {
                if (
                    $request->filled('ever_start_date') && $request->filled('ever_end_date')
                    && $request->filled('search_start_date') && $request->filled('search_end_date')
                ) {
                    $everStart = Carbon::createFromFormat('Y-m-d', trim((string) $request->ever_start_date))->startOfDay();
                    $everEnd = Carbon::createFromFormat('Y-m-d', trim((string) $request->ever_end_date))->startOfDay();
                    $searchStart = Carbon::createFromFormat('Y-m-d', trim((string) $request->search_start_date))->startOfDay();
                    $searchEnd = Carbon::createFromFormat('Y-m-d', trim((string) $request->search_end_date))->startOfDay();

                    if ($everEnd->lt($everStart) || $searchEnd->lt($searchStart)) {
                        return redirect()->back()
                            ->with('error', __('crm.err_end_before_start'))
                            ->withInput();
                    }

                    $tanggal_dari = $everStart->toDateString();
                    $tanggal_sampai = $everEnd->toDateString();
                    $tanggal_dari_2 = $searchStart->toDateString();
                    $tanggal_sampai_2 = $searchEnd->toDateString();
                } else {
                    $tanggal = $request->reservation;
                    $dari = explode('/', $tanggal);

                    $tanggal_2 = $request->reservation2;
                    $dari_2 = explode('/', $tanggal_2);

                    $tanggal_dari = $dari[2][0] . $dari[2][1] . $dari[2][2] . $dari[2][3] . '-' . $dari[0] . '-' . $dari[1];
                    $tanggal_sampai = $dari[4] . '-' . $dari[2][7] . $dari[2][8] . '-' . $dari[3];

                    $tanggal_dari_2 = $dari_2[2][0] . $dari_2[2][1] . $dari_2[2][2] . $dari_2[2][3] . '-' . $dari_2[0] . '-' . $dari_2[1];
                    $tanggal_sampai_2 = $dari_2[4] . '-' . $dari_2[2][7] . $dari_2[2][8] . '-' . $dari_2[3];
                }
            } catch (\Throwable $e) {
                return redirect()->back()
                    ->with('error', __('crm.err_invalid_date'))
                    ->withInput();
            }

            $quer = DB::table('transaksi_valid')->where('tanggal', '>=', $tanggal_dari . ' 00:00:00')->where('tanggal', '<=', $tanggal_sampai . ' 23:59:59')->whereNotIn('nama_penerima', function ($q) use ($tanggal_dari_2, $tanggal_sampai_2) {
                $q->select('nama_penerima')->from('transaksi_valid')->where('tanggal', '>=', $tanggal_dari_2 . ' 00:00:00')->where('tanggal', '<=', $tanggal_sampai_2 . ' 23:59:59')->whereNotNull('nama_penerima');
            })->groupBy('nama_penerima', 'no')->orderBy('tanggal', 'desc')->get();

            return view('checking.sales', [
                'tanggal_dari_2' => $tanggal_dari_2,
                'tanggal_sampai_2' => $tanggal_sampai_2,
                'data' => $quer,
                'tanggal_dari' => $tanggal_dari,
                'tanggal_sampai' => $tanggal_sampai,
                'ever_start_date' => $tanggal_dari,
                'ever_end_date' => $tanggal_sampai,
                'search_start_date' => $tanggal_dari_2,
                'search_end_date' => $tanggal_sampai_2,
            ]);
        }

        return redirect('login')->with('You dont have access');
    }


    public function newcustomer(Request $request)
    {
        if (! Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        DB::table('audit_trail_crm')->insertGetId([
            'username' => Auth::user()->email,
            'url' => url()->current(),
            'action' => 'Menu New Customer',
            'tanggal' => Carbon::now()->toDateTimeString(),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        $service = app(NewCustomerQueryService::class);

        return view('checking.newcustomer', [
            'channel' => $service->normalizeChannel($request->query('channel', 'all')),
            'direction' => $service->normalizeDirection($request->query('direction', 'all')),
            'start_date' => old('start_date', $request->query('start_date', '')),
            'end_date' => old('end_date', $request->query('end_date', '')),
        ]);
    }

    public function newcustomer_send(Request $request)
    {
        return redirect()->route(
            $request->routeIs('*operasional*') ? 'newcustomer_operasional' : 'newcustomer',
            [
                'channel' => $request->query('channel', 'pos'),
                'direction' => 'send',
            ]
        );
    }

    public function newcustomer_all(Request $request)
    {
        return redirect()->route('newcustomer', [
            'channel' => $request->query('channel', 'pos'),
            'direction' => 'all',
        ]);
    }

    public function search_newcustomer(Request $request)
    {
        try {
            [$tanggal_dari, $tanggal_sampai] = $this->resolveStartEndDates($request);
        } catch (InvalidArgumentException $e) {
            if ($request->ajax()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        $service = app(NewCustomerQueryService::class);
        $channel = $service->normalizeChannel($request->input('channel', 'all'));
        $direction = $service->normalizeDirection($request->input('direction', 'all'));
        $isOps = $request->routeIs('*operasional*');

        $rows = $service->fetch($channel, $direction, $tanggal_dari, $tanggal_sampai);

        if ($request->ajax()) {
            return DataTables::of($rows)
                ->addIndexColumn()
                ->addColumn('channel_label', fn ($row) => $row->channel_label ?? '')
                ->addColumn('direction_label', fn ($row) => $row->direction_label ?? '')
                ->addColumn('tanggal_fmt', fn ($row) => CrmTransactionDetail::formatDateMinute($row->tanggal ?? null))
                ->addColumn('jenis_kelamin_fmt', fn ($row) => CrmTransactionDetail::formatGender($row->jenis_kelamin ?? null))
                ->addColumn('tanggal_lahir_fmt', function ($row) {
                    return CrmTransactionDetail::formatDateOfBirth($row->tanggal_lahir ?? null);
                })
                ->addColumn('action', function ($row) use ($isOps) {
                    $routeName = $isOps
                        ? ($row->detail_operasional ?? null)
                        : ($row->detail_compliance ?? null);

                    if (! $routeName || empty($row->no)) {
                        return '';
                    }

                    try {
                        $url = route($routeName, $row->no);
                    } catch (\Throwable $e) {
                        return '';
                    }

                    return '<div class="crm-actions">'
                        .'<a href="'.e($url).'" class="crm-btn crm-btn-sm crm-btn-ghost crm-btn-detail" target="_blank" rel="noopener">'
                        .e(__('crm.btn_detail'))
                        .'</a>'
                        .'</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return redirect()->route(
            $isOps ? 'newcustomer_operasional' : 'newcustomer',
            [
                'channel' => $channel,
                'direction' => $direction,
                'start_date' => $tanggal_dari,
                'end_date' => $tanggal_sampai,
            ]
        );
    }

    public function search_newcustomer_all(Request $request)
    {
        $request->merge([
            'channel' => $request->input('channel', 'pos'),
            'direction' => $request->input('direction', 'all'),
        ]);

        return $this->search_newcustomer($request);
    }

<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
    public function search_newcustomer_send(Request $request)
    {
        $request->merge([
            'channel' => $request->input('channel', 'pos'),
            'direction' => 'send',
        ]);

        return $this->search_newcustomer($request);
    }

    public function screening(Request $request)
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu New Screening",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            return view('checking.screening', [

            ]);


        }

        return redirect('login')->with('You dont have access');
    }

    public function cari_screening(Request $request)
    {
        if (!Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        try {
            [$tanggal_dari, $tanggal_sampai] = $this->resolveStartEndDates($request);
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        $cari = 'ya';
        $quer = DB::table('transaksi_valid')
            ->where('deltawork', '=', 'ya')
            ->where('tanggal', '>=', $tanggal_dari . ' 00:00:00')
            ->where('tanggal', '<=', $tanggal_sampai . ' 23:59:59')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('checking.screening', [
            'tanggal_sampai' => $tanggal_sampai,
            'tanggal_dari' => $tanggal_dari,
            'data' => $quer,
            'cari' => $cari,
        ]);
    }

    public function cari_screening_operasional(Request $request)
    {
        if (!Auth::check()) {
            return redirect('login')->with('You dont have access');
        }

        try {
            [$tanggal_dari, $tanggal_sampai] = $this->resolveStartEndDates($request);
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        $cari = 'ya';
        $quer = DB::table('transaksi_valid')
            ->where('deltawork', '=', 'ya')
            ->where('tanggal', '>=', $tanggal_dari . ' 00:00:00')
            ->where('tanggal', '<=', $tanggal_sampai . ' 23:59:59')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('checking.screening', [
            'tanggal_sampai' => $tanggal_sampai,
            'tanggal_dari' => $tanggal_dari,
            'data' => $quer,
            'cari' => $cari,
        ]);
    }

    public function sanction(Request $request)
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu Sanction List",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            if ($request->ajax()) {
                $data = DB::table('sanction_list')
                    ->select(
                        'id',
                        'nama',
                        'alias',
                        DB::raw('COALESCE(tanggal_lahir, \'\') as tanggal_lahir'),
                        'negara',
                        'no_ktp',
                        'nohp',
                        'keterangan',
                        'alamat',
                        'type'
                    )
                    ->orderBy('id', 'desc')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        //$actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        //return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('sanctions.sanction', ['tambah' => null]);


        }

        return redirect('login')->with('You dont have access');
    }

    public function sanction_operasional(Request $request)
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu Sanction List",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            if ($request->ajax()) {
                $data = DB::table('sanction_list')
                    ->select(
                        'id',
                        'nama',
                        'alias',
                        DB::raw('COALESCE(tanggal_lahir, \'\') as tanggal_lahir'),
                        'negara',
                        'no_ktp',
                        'nohp',
                        'keterangan',
                        'alamat',
                        'type'
                    )
                    ->orderBy('id', 'desc')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        //$actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        //return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('sanctions.sanction_operasional', ['tambah' => null]);


        }

        return redirect('login')->with('You dont have access');
    }


    public function tambah_sanction(Request $request)
    {
        if (Auth::check()) {
            $tambah = "ya";
            return view('sanctions.sanction', [
                'tambah' => $tambah,
            ]);
        }

        return redirect('login')->with('You dont have access');
    }
    public function tambah_sanction_operasional(Request $request)
    {
        if (Auth::check()) {
            $tambah = "ya";
            return view('sanctions.sanction_operasional', [
                'tambah' => $tambah,
            ]);
        }

        return redirect('login')->with('You dont have access');
    }

    public function act_tambah_sanction(Request $request)
    {
        if (Auth::check()) {
            $nama = $request->nama;
            $alias = $request->alias;
            $tgl_lahir = $request->tgl_lahir;
            $alamat = $request->alamat;
            $asalnegara = $request->asalnegara;
            $nomor_identitas = $request->nomor_identitas;
            $telepon_penerima = $request->telepon_penerima;
            $keterangan = $request->keterangan;
            $kategori = $request->kategori;
            $cekId = DB::table('sanction_list')->orderBy('id', 'desc')->first();
            if ($cekId == null) {
                $idNya = 1;
            } else {
                $idNya = $cekId->id + 1;
            }
            $qur = DB::table('sanction_list')->insertGetId([
                'id' => $idNya,
                'nama' => $nama,
                'alias' => $alias,
                'tanggal_lahir' => $tgl_lahir,
                'alamat' => $alamat,
                'negara' => $asalnegara,
                'no_ktp' => $nomor_identitas,
                'nohp' => $telepon_penerima,
                'keterangan' => $keterangan,
                'type' => $kategori,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            if ($qur) {
                return redirect('sanction')->with('success', 'You have successfully add data');
            }


        }

        return redirect('login')->with('You dont have access');
    }

    public function act_tambah_sanction_operasional(Request $request)
    {
        if (Auth::check()) {
            $nama = $request->nama;
            $alias = $request->alias;
            $tgl_lahir = $request->tgl_lahir;
            $alamat = $request->alamat;
            $asalnegara = $request->asalnegara;
            $nomor_identitas = $request->nomor_identitas;
            $telepon_penerima = $request->telepon_penerima;
            $keterangan = $request->keterangan;
            $kategori = $request->kategori;
            $cekId = DB::table('sanction_list')->orderBy('id', 'desc')->first();
            if ($cekId == null) {
                $idNya = 1;
            } else {
                $idNya = $cekId->id + 1;
            }
            $qur = DB::table('sanction_list')->insertGetId([
                'id' => $idNya,
                'nama' => $nama,
                'alias' => $alias,
                'tanggal_lahir' => $tgl_lahir,
                'alamat' => $alamat,
                'negara' => $asalnegara,
                'no_ktp' => $nomor_identitas,
                'nohp' => $telepon_penerima,
                'keterangan' => $keterangan,
                'type' => $kategori,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            if ($qur) {
                return redirect('sanction_operasional')->with('success', 'You have successfully add data');
            }


        }

        return redirect('login')->with('You dont have access');
    }



    public function filtering()
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu High Risk Country Transaction",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            return view('reporting.filtering', [

            ]);


        }

        return redirect('login')->with('You dont have access');
    }


    public function cari_filtering(Request $request)
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $cari_filtering_s = "ya";

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $tanggal_dari = $request->input('start_date');
                $tanggal_sampai = $request->input('end_date');
            } else {
                $tanggal = $request->reservation;
                $dari = explode('/', $tanggal);
                $tanggal_dari = $dari[2][0] . $dari[2][1] . $dari[2][2] . $dari[2][3] . '-' . $dari[0] . '-' . $dari[1];
                $tanggal_sampai = $dari[4] . '-' . $dari[2][7] . $dari[2][8] . '-' . $dari[3];
            }

            $query = DB::table('transaksi_valid')
                ->join('high_risk_country', 'high_risk_country.nama', '=', 'transaksi_valid.negara_pengirim')
                ->where('id_transaksi', '<>', '')
                ->where('transaksi_valid.tanggal', '>=', $tanggal_dari . ' 00:00:00')
                ->where('transaksi_valid.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
                ->orderBy('transaksi_valid.negara_pengirim', 'desc')
                ->get();

            return view('reporting.filtering', [
                'data' => $query,
                'cari_s' => $cari_filtering_s,
                'tanggal_dari' => $tanggal_dari,
                'tanggal_sampai' => $tanggal_sampai,
            ]);
        }

        return redirect('login')->with('You dont have access');
    }

    public function newcustomer_receive_analytics(
        Request $request,
        CustomerAnalyticsService $analytics
    ) {
        $data = collect();
        $hasFilter = false;
        $hasAnyDate = $request->filled('start_date') || $request->filled('end_date');

        if ($hasAnyDate) {
            $validator = $this->customerAnalyticsDateValidator($request);

            if ($validator->fails()) {
                return view('checking.newcustomer_receive_analytics', compact(
                    'data',
                    'hasFilter'
                ))->withErrors($validator);
            }

            [$startDate, $endDateExclusive] = $this->customerAnalyticsDateRange($request);

            $data = $analytics
                ->newReceiveCustomers($startDate, $endDateExclusive)
                ->paginate(10)
                ->withQueryString();

            $hasFilter = true;
        }

        return view('checking.newcustomer_receive_analytics', compact(
            'data',
            'hasFilter'
        ));
    }

    public function counting_customer_send(
        Request $request,
        CustomerAnalyticsService $analytics
    ) {
        $data = collect();
        $hasFilter = false;
        $hasAnyDate = $request->filled('start_date') || $request->filled('end_date');

        if ($hasAnyDate) {
            $validator = $this->customerAnalyticsDateValidator($request);

            if ($validator->fails()) {
                return view('checking.counting_customer_send', compact(
                    'data',
                    'hasFilter'
                ))->withErrors($validator);
            }

            [$startDate, $endDateExclusive] = $this->customerAnalyticsDateRange($request);

            $data = $analytics
                ->customerTransactionSummary($startDate, $endDateExclusive)
                ->paginate(10)
                ->withQueryString();

            $hasFilter = true;
        }

        return view('checking.counting_customer_send', compact(
            'data',
            'hasFilter'
        ));
    }

    public function counting_customer_send_detail(
        Request $request,
        CustomerAnalyticsService $analytics
    ) {
        $validator = Validator::make(
            $request->only(['nomor_identitas', 'start_date', 'end_date']),
            [
                'nomor_identitas' => ['required', 'string'],
                'start_date' => ['required', 'date_format:Y-m-d'],
                'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            ]
        );

        if ($validator->fails()) {
            abort(422, $validator->errors()->first());
        }

        $nomorIdentitas = trim($request->input('nomor_identitas'));
        [$startDate, $endDateExclusive] = $this->customerAnalyticsDateRange($request);

        $transactions = $analytics
            ->customerTransactions($nomorIdentitas, $startDate, $endDateExclusive)
            ->paginate(10)
            ->withQueryString();

        $first = $transactions->first();
        $customerName = $first->nama_pengirim ?? '-';
        $customerGender = $first->jenis_kelamin ?? null;
        $customerPlaceOfBirth = $first->kota_lahir_pengirim ?? null;
        $customerDateOfBirth = $first->tanggal_lahir ?? null;
        $customerPhone = $first->telpon_pengirim ?? null;
        $detailRoute = Auth::user()->level === 'operasional'
            ? 'transaksi_send_pos_operasional.detail'
            : 'transaksi_send_pos.detail';

        return view('checking.counting_customer_send_detail', compact(
            'transactions',
            'nomorIdentitas',
            'customerName',
            'customerGender',
            'customerPlaceOfBirth',
            'customerDateOfBirth',
            'customerPhone',
            'detailRoute'
        ));
    }

    public function newcustomer_daily(
        Request $request,
        CustomerAnalyticsService $analytics
    ) {
        $data = collect();
        $hasFilter = false;
        $hasAnyDate = $request->filled('start_date') || $request->filled('end_date');

        if ($hasAnyDate) {
            $validator = $this->customerAnalyticsDateValidator($request);

            if ($validator->fails()) {
                return view('checking.newcustomer_send_analytics', compact(
                    'data',
                    'hasFilter'
                ))->withErrors($validator);
            }

            [$startDate, $endDateExclusive] = $this->customerAnalyticsDateRange($request);

            $data = $analytics
                ->newSendCustomers($startDate, $endDateExclusive)
                ->paginate(10)
                ->withQueryString();

            $hasFilter = true;
        }

        return view('checking.newcustomer_send_analytics', compact(
            'data',
            'hasFilter'
        ));
    }

    public function newcustomer_monthly(Request $request)
    {
        if ($request->filled('bulan')) {
            $tanggalAwal = Carbon::createFromFormat('Y-m', $request->input('bulan'));
        } else {
            $offsetBulan = max(0, (int) $request->input('offset', 0));
            $tanggalAwal = Carbon::today()->subMonths($offsetBulan);
        }

        $tanggalDari = $tanggalAwal->copy()->startOfMonth()->format('Y-m-d');
        $tanggalSampai = $tanggalAwal->copy()->endOfMonth()->format('Y-m-d');
        $tanggalBesok = Carbon::parse($tanggalSampai)->addDay()->format('Y-m-d');

        $data = DB::table('transaksi_send_pos as t')
            ->select(
                't.id_transaksi',
                't.nama_pengirim as nama_pengirim',
                't.tanggal as tanggal_transaksi',
                't.no_referensi as no_referensi',
                't.id_pos as nopend',
                't.negara_penerima as negara_tujuan',
                't.amount as jumlah_transaksi',
                't.telpon_pengirim as nomor_telepon',
                't.nomor_identitas as nomor_identitas'
            )
            ->where('t.tanggal', '>=', $tanggalDari . ' 00:00:00')
            ->where('t.tanggal', '<', $tanggalBesok . ' 00:00:00')
            ->whereNotNull('t.nomor_identitas')
            ->where('t.nomor_identitas', '<>', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('transaksi_send_pos as old')
                    ->whereColumn('old.nomor_identitas', 't.nomor_identitas')
                    ->where(function ($q) {
                        $q->whereColumn('old.tanggal', '<', 't.tanggal')
                            ->orWhere(function ($q2) {
                                $q2->whereColumn('old.tanggal', 't.tanggal')
                                    ->whereColumn('old.id_transaksi', '<', 't.id_transaksi');
                            });
                    });
            })
            ->orderBy('t.tanggal', 'asc')
            ->orderBy('t.id_transaksi', 'asc')
            ->get();

        return view(
            'checking.newcustomer_monthly',
            compact('data', 'tanggalDari', 'tanggalSampai')
        );
    }

    private function customerAnalyticsDateValidator(Request $request)
    {
        return Validator::make(
            $request->only(['start_date', 'end_date']),
            [
                'start_date' => ['required', 'date_format:Y-m-d'],
                'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            ],
            [
                'start_date.required' => __('crm.err_start_required'),
                'start_date.date_format' => __('crm.err_invalid_date'),
                'end_date.required' => __('crm.err_end_required'),
                'end_date.date_format' => __('crm.err_invalid_date'),
                'end_date.after_or_equal' => __('crm.err_end_before_start'),
            ]
        );
    }

    private function customerAnalyticsDateRange(Request $request): array
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))
            ->startOfDay();
        $endDateExclusive = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))
            ->addDay()
            ->startOfDay();

        return [$startDate, $endDateExclusive];
    }

    /**
     * @return array{0: string, 1: string} [start Y-m-d, end Y-m-d]
     */
    private function resolveStartEndDates(Request $request): array
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', trim((string) $request->start_date))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', trim((string) $request->end_date))->startOfDay();
            } catch (\Throwable $e) {
                throw new InvalidArgumentException(__('crm.err_invalid_date'));
            }

            if ($end->lt($start)) {
                throw new InvalidArgumentException(__('crm.err_end_before_start'));
            }

            return [$start->toDateString(), $end->toDateString()];
        }

        $reservation = str_replace('+-+', ' - ', (string) $request->input('reservation', ''));
        if (!str_contains($reservation, ' - ')) {
            throw new InvalidArgumentException(__('crm.err_dates_required'));
        }

        try {
            [$dari, $sampai] = explode(' - ', $reservation, 2);

            return [
                Carbon::createFromFormat('m/d/Y', trim($dari))->format('Y-m-d'),
                Carbon::createFromFormat('m/d/Y', trim($sampai))->format('Y-m-d'),
            ];
        } catch (\Throwable $e) {
            throw new InvalidArgumentException(__('crm.err_invalid_date'));
        }
    }



    // public function returnCustomer(Request $request)
    // {
    //     // Receive POS #1
    //     $receivePos = DB::table('transaksi_valid')
    //         ->where('transaksi_valid.deltawork', 'ya')
    //         ->whereNotNull('transaksi_valid.id_transaksi')
    //         ->whereNotNull('transaksi_valid.nomor_identitas')
    //         ->whereRaw("trim(transaksi_valid.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_valid.nomor_identitas) as nomor_identitas"),
    //             'transaksi_valid.nama_penerima as nama'
    //         );

    //     // Send POS #2
    //     $sendPos = DB::table('transaksi_send_pos')
    //         ->whereNotNull('transaksi_send_pos.nomor_identitas')
    //         ->whereRaw("trim(transaksi_send_pos.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_send_pos.nomor_identitas) as nomor_identitas"),
    //             'transaksi_send_pos.nama_pengirim as nama'
    //         );

    //     // Send Branch #3
    //     $sendBranch = DB::table('transaksi_send')
    //         ->whereNotNull('transaksi_send.nomor_identitas')
    //         ->whereRaw("trim(transaksi_send.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_send.nomor_identitas) as nomor_identitas"),
    //             'transaksi_send.nama_pengirim as nama'
    //         );

    //     // Receive Branch #4
    //     $receiveBranch = DB::table('transaksi_receive')
    //         ->whereNotNull('transaksi_receive.nomor_identitas')
    //         ->whereRaw("trim(transaksi_receive.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_receive.nomor_identitas) as nomor_identitas"),
    //             'transaksi_receive.nama_penerima as nama'
    //         );

    //     // Receive Direct #5
    //     $receiveDirect = DB::table('transaksi_receive_clients')
    //         ->whereNotNull('transaksi_receive_clients.nomor_identitas')
    //         ->whereRaw("trim(transaksi_receive_clients.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_receive_clients.nomor_identitas) as nomor_identitas"),
    //             'transaksi_receive_clients.nama_penerima as nama'
    //         );

    //     // Send Direct #6
    //     $sendDirect = DB::table('transaksi_send_clients')
    //         ->whereNotNull('transaksi_send_clients.nomor_identitas')
    //         ->whereRaw("trim(transaksi_send_clients.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_send_clients.nomor_identitas) as nomor_identitas"),
    //             'transaksi_send_clients.nama_pengirim as nama'
    //         );

    //     // Merge all transactions with UNION ALL
    //     $allTransactions = $receivePos
    //         ->unionAll($sendPos)
    //         ->unionAll($sendBranch)
    //         ->unionAll($receiveBranch)
    //         ->unionAll($receiveDirect)
    //         ->unionAll($sendDirect);

    //     // Count transaction by nomor_identitas
    //     $customers = DB::query()
    //         ->fromSub($allTransactions, 'transactions')
    //         ->selectRaw("
    //             nomor_identitas,
    //             MAX(nama) AS nama,
    //             COUNT(*) AS count_transaction
    //         ")
    //         ->groupBy('nomor_identitas')
    //         ->orderByDesc('count_transaction')
    //         ->get();

    //     return view('checking.return_customer', [
    //         'customers' => $customers,
    //     ]);
    // }


    public function returnCustomer(Request $request)
    {
        return view('checking.return_customer');
    }


    public function search_returncustomer(Request $request)
    {

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $filterBy = $request->input('filter_by', 'nama');
        $search = trim((string) $request->input('nama', ''));

        $allowedFilters = ['nama', 'nomor_identitas'];

        if (!in_array($filterBy, $allowedFilters, true)) {
            abort(422, 'Invalid search filter.');
        }

        // VALIDATE SEARCH INPUT
        if ($search !== '') {
            $containsDigit = preg_match('/\d/', $search) === 1;

            if ($filterBy === 'nama' && $containsDigit) {
                abort(
                    422,
                    'Your search input looks like an ID number. Please select ID Number.'
                );
            }

            if ($filterBy === 'nomor_identitas' && !$containsDigit) {
                abort(
                    422,
                    'Your search input looks like a customer name. Please select Name.'
                );
            }
        }

        // Receive POS
        $receivePos = DB::table('transaksi_valid')
            ->where('transaksi_valid.deltawork', 'ya')
            ->whereNotNull('transaksi_valid.id_transaksi')
            ->whereNotNull('transaksi_valid.nomor_identitas')
            ->whereRaw("trim(transaksi_valid.nomor_identitas) <> ''")

            ->when($startDate, function ($query) use ($startDate) {
                $query->where(
                    'transaksi_valid.tanggal',
                    '>=',
                    $startDate
                )
                ;
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where(
                    'transaksi_valid.tanggal',
                    '<',
                    Carbon::parse($endDate)->addDay()
                );
            })

            ->select(
                DB::raw("trim(transaksi_valid.nomor_identitas) as nomor_identitas"),
                'transaksi_valid.nama_penerima as nama'
            );


        // Send POS
        $sendPos = DB::table('transaksi_send_pos')
            ->whereNotNull('transaksi_send_pos.nomor_identitas')
            ->whereRaw("trim(transaksi_send_pos.nomor_identitas) <> ''")

            ->when($startDate, function ($query) use ($startDate) {
                $query->where(
                    'transaksi_send_pos.tanggal',
                    '>=',
                    $startDate
                )
                ;
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where(
                    'transaksi_send_pos.tanggal',
                    '<',
                    Carbon::parse($endDate)->addDay()
                );
            })

            ->select(
                DB::raw("trim(transaksi_send_pos.nomor_identitas) as nomor_identitas"),
                'transaksi_send_pos.nama_pengirim as nama'
            );


        // Send Branch
        $sendBranch = DB::table('transaksi_send')
            ->whereNotNull('transaksi_send.nomor_identitas')
            ->whereRaw("trim(transaksi_send.nomor_identitas) <> ''")

            ->when($startDate, function ($query) use ($startDate) {
                $query->where(
                    'transaksi_send.tanggal',
                    '>=',
                    $startDate
                )
                ;
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where(
                    'transaksi_send.tanggal',
                    '<',
                    Carbon::parse($endDate)->addDay()
                );
            })

            ->select(
                DB::raw("trim(transaksi_send.nomor_identitas) as nomor_identitas"),
                'transaksi_send.nama_pengirim as nama'
            );

        // Receive Branch
        $receiveBranch = DB::table('transaksi_receive')
            ->whereNotNull('transaksi_receive.nomor_identitas')
            ->whereRaw("trim(transaksi_receive.nomor_identitas) <> ''")

            ->when($startDate, function ($query) use ($startDate) {
                $query->where(
                    'transaksi_receive.tanggal',
                    '>=',
                    $startDate
                );
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where(
                    'transaksi_receive.tanggal',
                    '<',
                    Carbon::parse($endDate)->addDay()
                );
            })

            ->select(
                DB::raw("trim(transaksi_receive.nomor_identitas) as nomor_identitas"),
                'transaksi_receive.nama_penerima as nama'
            );

        // Receive Client
        $receiveClient = DB::table('transaksi_receive_clients')
            ->whereNotNull('transaksi_receive_clients.nomor_identitas')
            ->whereRaw("trim(transaksi_receive_clients.nomor_identitas) <> ''")
            ->when($startDate, function ($query) use ($startDate) {
                $query->where(
                    'transaksi_receive_clients.tanggal',
                    '>=',
                    $startDate
                );
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where(
                    'transaksi_receive_clients.tanggal',
                    '<',
                    Carbon::parse($endDate)->addDay()
                );
            })

            ->select(
                DB::raw("trim(transaksi_receive_clients.nomor_identitas) as nomor_identitas"),
                'transaksi_receive_clients.nama_penerima as nama'
            );

        // Send Client
        $sendClient = DB::table('transaksi_send_clients')
            ->whereNotNull('transaksi_send_clients.nomor_identitas')
            ->whereRaw("trim(transaksi_send_clients.nomor_identitas) <> ''")
            ->when($startDate, function ($query) use ($startDate) {
                $query->where(
                    'transaksi_send_clients.tanggal',
                    '>=',
                    $startDate
                );
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where(
                    'transaksi_send_clients.tanggal',
                    '<',
                    Carbon::parse($endDate)->addDay()
                );
            })

            ->select(
                DB::raw("trim(transaksi_send_clients.nomor_identitas) as nomor_identitas"),
                'transaksi_send_clients.nama_pengirim as nama'
            );

        // UNION ALL
        $allTransactions = $receivePos
            ->unionAll($sendPos)
            ->unionAll($sendBranch)
            ->unionAll($receiveBranch)
            ->unionAll($receiveClient)
            ->unionAll($sendClient);

        // GROUP BY nomor_identitas
        $groupedCustomers = DB::query()
            ->fromSub($allTransactions, 'transactions')
            ->selectRaw("
        nomor_identitas,
        MAX(nama) AS nama,
        COUNT(*) AS count_transaction
    ")
            ->groupBy('nomor_identitas');


        // APPLY SEARCH
        $customers = DB::query()
            ->fromSub($groupedCustomers, 'customers')
            ->when($search !== '', function ($query) use ($filterBy, $search) {
                $query->where(
                    $filterBy,
                    'ILIKE',
                    '%' . $search . '%'
                );
            });

        // DEFAULT ORDER
        $customers->orderByDesc('count_transaction');

        // DATATABLE RESPONSE
        return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('action', function ($customer) use ($startDate, $endDate) {
                $url = route('detail_transaction_return_customer', [
                    'nomor_identitas' => $customer->nomor_identitas,
                    'nama' => $customer->nama,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);

                return '
                    <a href="' . e($url) . '"
                        class="crm-btn crm-btn-primary crm-btn-detail"
                        title="Detail">
                        Detail
                    </a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);

    }



    // public function detail_transaction_return_customer(Request $request)
    // {
    //     $nomorIdentitas = trim((string) $request->input('nomor_identitas'));
    //     $nama = trim((string) $request->input('nama'));

    //     // ==========================================
    //     // QUERY TRANSAKSI RETURN CUSTOMER
    //     // ==========================================

    //     // Receive Direct
    //     $queryReceive = DB::table('transaksi_receive')
    //         ->where('nomor_identitas', $nomorIdentitas)
    //         ->get();

    //     // Send Direct
    //     $querySend = DB::table('transaksi_send')
    //         ->where('nomor_identitas', $nomorIdentitas)
    //         ->get();

    //     // Receive POS
    //     $dat = DB::table('transaksi_valid')
    //         ->where('nomor_identitas', $nomorIdentitas)
    //         ->where('deltawork', 'ya')
    //         ->whereNotNull('id_transaksi')
    //         ->get();

    //     // Send POS
    //     $querySendPos = DB::table('transaksi_send_pos')
    //         ->where('nomor_identitas', $nomorIdentitas)
    //         ->get();

    //     // Receive Branch
    //     $queryReceiveClient = DB::table('transaksi_receive_clients')
    //         ->where('nomor_identitas', $nomorIdentitas)
    //         ->get();

    //     // Send Branch
    //     $querySendClient = DB::table('transaksi_send_clients')
    //         ->where('nomor_identitas', $nomorIdentitas)
    //         ->get();

    //     return view('checking.detail_transaction_return_customer', [
    //         'hasDetail' => true,
    //         'nama' => $nama,
    //         'dat' => $dat,
    //         'queryReceive' => $queryReceive,
    //         'querySend' => $querySend,
    //         'querySendPos' => $querySendPos,
    //         'queryReceiveClient' => $queryReceiveClient,
    //         'querySendClient' => $querySendClient,
    //     ]);
    // } // false


    public function detail_transaction_return_customer(Request $request)
    {
        $nomorIdentitas = trim((string) $request->input('nomor_identitas'));
        $nama = trim((string) $request->input('nama'));
        $namaNorm = preg_replace('/\s+/', ' ', $nama);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($nomorIdentitas === '' && $namaNorm === '') {
            return redirect()->route('return_customer', $request->only('start_date', 'end_date'));
        }

        // NIK diutamakan; fallback ke nama (ternormalisasi) bila NIK kosong.
        $applyMatch = function ($query, $nikCol, $namaCol) use ($nomorIdentitas, $namaNorm) {
            if ($nomorIdentitas !== '') {
                $query->whereRaw("trim($nikCol) = ?", [$nomorIdentitas]);
            } else {
                $query->whereRaw("regexp_replace(btrim($namaCol), '\\s+', ' ', 'g') = ?", [$namaNorm]);
            }
            return $query;
        };

        // Filter periode: sama persis dengan search_returncustomer
        $applyDate = function ($query, $tanggalCol) use ($startDate, $endDate) {
            if ($startDate) {
                $query->where($tanggalCol, '>=', $startDate);
            }
            if ($endDate) {
                $query->where($tanggalCol, '<', Carbon::parse($endDate)->addDay());
            }
            return $query;
        };

        // Receive Direct
        $queryReceive = DB::table('transaksi_receive');
        $applyMatch($queryReceive, 'transaksi_receive.nomor_identitas', 'transaksi_receive.nama_penerima');
        $applyDate($queryReceive, 'transaksi_receive.tanggal');
        $queryReceive = $queryReceive->get();

        // Send Direct
        $querySend = DB::table('transaksi_send');
        $applyMatch($querySend, 'transaksi_send.nomor_identitas', 'transaksi_send.nama_pengirim');
        $applyDate($querySend, 'transaksi_send.tanggal');
        $querySend = $querySend->get();

        // Receive Pos (butuh alias bsu & tgl_valid untuk view)
        $dat = DB::table('transaksi_valid')
            ->where('transaksi_valid.deltawork', 'ya')
            ->whereNotNull('transaksi_valid.id_transaksi');
        $applyMatch($dat, 'transaksi_valid.nomor_identitas', 'transaksi_valid.nama_penerima');
        $applyDate($dat, 'transaksi_valid.tanggal');
        $dat = $dat->select(
            'transaksi_valid.*',
            DB::raw("replace(transaksi_valid.bsubanyak, ',', '') as bsu"),
            DB::raw('transaksi_valid.tanggal as tgl_valid')
        )->get();

        // Send Pos
        $querySendPos = DB::table('transaksi_send_pos');
        $applyMatch($querySendPos, 'transaksi_send_pos.nomor_identitas', 'transaksi_send_pos.nama_pengirim');
        $applyDate($querySendPos, 'transaksi_send_pos.tanggal');
        $querySendPos = $querySendPos->get();

        // Receive Branch
        $queryReceiveClient = DB::table('transaksi_receive_clients');
        $applyMatch($queryReceiveClient, 'transaksi_receive_clients.nomor_identitas', 'transaksi_receive_clients.nama_penerima');
        $applyDate($queryReceiveClient, 'transaksi_receive_clients.tanggal');
        $queryReceiveClient = $queryReceiveClient->get();

        // Send Branch
        $querySendClient = DB::table('transaksi_send_clients');
        $applyMatch($querySendClient, 'transaksi_send_clients.nomor_identitas', 'transaksi_send_clients.nama_pengirim');
        $applyDate($querySendClient, 'transaksi_send_clients.tanggal');
        $querySendClient = $querySendClient->get();

        $transactionCount =
            $dat->count() +
            $queryReceiveClient->count() +
            $queryReceive->count() +
            $querySend->count() +
            $querySendPos->count() +
            $querySendClient->count();

        // Isi listing profil: pakai input request, kalau kosong ambil dari baris pertama yang terisi.
        $pick = function (string $field, string $fromRequest = '') use ($request, $dat, $queryReceiveClient, $queryReceive, $querySend, $querySendPos, $querySendClient) {
            $v = trim((string) $request->input($field, $fromRequest));
            if ($v !== '') {
                return $v;
            }
            foreach ([$dat, $queryReceiveClient, $queryReceive, $querySend, $querySendPos, $querySendClient] as $rows) {
                foreach ($rows as $row) {
                    $v = trim((string) ($row->$field ?? ''));
                    if ($v !== '') {
                        return $v;
                    }
                }
            }
            return '';
        };

        return view('checking.return_customer', [
            'hasDetail' => true,
            'nama' => $nama,
            'transactionCount' => $transactionCount,
            'dat' => $dat,
            'queryReceive' => $queryReceive,
            'querySend' => $querySend,
            'querySendPos' => $querySendPos,
            'queryReceiveClient' => $queryReceiveClient,
            'querySendClient' => $querySendClient,
            'profilListing' => [
                'nama' => $nama,
                'alamat_penerima' => $pick('alamat_penerima'),
                'telepon_penerima' => $pick('telepon_penerima'),
                'tanggal_lahir' => $pick('tanggal_lahir'),
                'nomor_identitas' => $nomorIdentitas,
                // 'transactionCount' => $transactionCount,
            ],
        ]);
    }

    // public function search_returncustomer(Request $request)
    // {

    //     // Receive POS
    //     $receivePos = DB::table('transaksi_valid')
    //         ->where('transaksi_valid.deltawork', 'ya')
    //         ->whereNotNull('transaksi_valid.id_transaksi')
    //         ->whereNotNull('transaksi_valid.nomor_identitas')
    //         ->whereRaw("trim(transaksi_valid.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_valid.nomor_identitas) as nomor_identitas"),
    //             'transaksi_valid.nama_penerima as nama'
    //         );

    //     // Send POS
    //     $sendPos = DB::table('transaksi_send_pos')
    //         ->whereNotNull('transaksi_send_pos.nomor_identitas')
    //         ->whereRaw("trim(transaksi_send_pos.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_send_pos.nomor_identitas) as nomor_identitas"),
    //             'transaksi_send_pos.nama_pengirim as nama'
    //         );

    //     // Send Branch
    //     $sendBranch = DB::table('transaksi_send')
    //         ->whereNotNull('transaksi_send.nomor_identitas')
    //         ->whereRaw("trim(transaksi_send.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_send.nomor_identitas) as nomor_identitas"),
    //             'transaksi_send.nama_pengirim as nama'
    //         );

    //     // Receive Branch
    //     $receiveBranch = DB::table('transaksi_receive')
    //         ->whereNotNull('transaksi_receive.nomor_identitas')
    //         ->whereRaw("trim(transaksi_receive.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_receive.nomor_identitas) as nomor_identitas"),
    //             'transaksi_receive.nama_penerima as nama'
    //         );

    //     // Receive Client
    //     $receiveClient = DB::table('transaksi_receive_clients')
    //         ->whereNotNull('transaksi_receive_clients.nomor_identitas')
    //         ->whereRaw("trim(transaksi_receive_clients.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_receive_clients.nomor_identitas) as nomor_identitas"),
    //             'transaksi_receive_clients.nama_penerima as nama'
    //         );

    //     // Send Client
    //     $sendClient = DB::table('transaksi_send_clients')
    //         ->whereNotNull('transaksi_send_clients.nomor_identitas')
    //         ->whereRaw("trim(transaksi_send_clients.nomor_identitas) <> ''")
    //         ->select(
    //             DB::raw("trim(transaksi_send_clients.nomor_identitas) as nomor_identitas"),
    //             'transaksi_send_clients.nama_pengirim as nama'
    //         );

    //     // Gabungkan seluruh transaksi
    //     $allTransactions = $receivePos
    //         ->unionAll($sendPos)
    //         ->unionAll($sendBranch)
    //         ->unionAll($receiveBranch)
    //         ->unionAll($receiveClient)
    //         ->unionAll($sendClient);

    //     // Group berdasarkan nomor_identitas
    //     $customers = DB::query()
    //         ->fromSub($allTransactions, 'transactions')
    //         ->selectRaw("
    //             nomor_identitas,
    //             MAX(nama) AS nama,
    //             COUNT(*) AS count_transaction
    //         ")
    //         ->groupBy('nomor_identitas');

    //     return DataTables::of($customers)
    //         ->addIndexColumn()
    //         ->addColumn('action', function ($customer) {

    //             return '
    //                 <a href="' . route('detail_transaksi', [
    //                     'nomor_identitas' => $customer->nomor_identitas,
    //                     'nama' => $customer->nama,
    //                 ]) . '"
    //                 class="btn btn-sm btn-primary"
    //                 title="Detail">
    //                     <i class="fas fa-eye"></i>
    //                 </a>
    //             ';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);

    //     return response()->json([
    //         'message' => 'You dont have access'
    //     ], 401);
    // }

}



