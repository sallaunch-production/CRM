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
use DB;
use Carbon\Carbon;
use InvalidArgumentException;

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
                    return redirect()->back()->with('error', 'Format tanggal tidak valid.')->withInput();
                }

                if ($end->lt($start)) {
                    return redirect()->back()->with('error', 'End date tidak boleh lebih kecil dari start date.')->withInput();
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
        //  cek berhasil login 
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
                'lapor' => ['nullable', 'string', 'max:255'],
                'remark' => ['nullable', 'string', 'max:255'],
            ]);

            DB::table('profil')
                ->where('id', $validated['id'])
                ->update([
                    'lapor' => $validated['lapor'] ?? null,
                    'remark' => $validated['remark'] ?? null,

                    'lapor_updated_at' => Carbon::now(),
                    'lapor_updated_by' => Auth::id(),
                ]);

            return redirect()->back()->with(
                'success',
                'Profil customer berhasil diperbarui.'
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
                            ->with('error', 'End date tidak boleh lebih kecil dari start date.')
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
                    ->with('error', 'Format tanggal tidak valid.')
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


    public function newcustomer()
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $send = " ";
            $data = " ";
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu New Customer",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            return view('checking.newcustomer_new', ['send' => $send, 'data' => $data]);
        }

        return redirect('login')->with('You dont have access');
    }

    public function newcustomer_send()
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $send = "ya";
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu New Customer Send",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            return view('checking.newcustomersend', ['send' => $send]);
        }

        return redirect('login')->with('You dont have access');
    }

    public function newcustomer_all()
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $send = "ya";
            $qur = DB::table('audit_trail_crm')->insertGetId([
                'username' => Auth::user()->email,
                'url' => url()->current(),
                'action' => "Menu New Customer All",
                'tanggal' => Carbon::now()->toDateTimeString(),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            return view('checking.newcustomerall', ['send' => $send]);
        }

        return redirect('login')->with('You dont have access');
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

        $query = DB::table('transaksi_valid as t1')
            ->select(
                DB::raw('MIN(t1.no) AS no'),
                't1.nama_penerima',
                DB::raw('MIN(t1.no_referensi) AS no_referensi'),
                DB::raw('MIN(t1.tanggal) AS tanggal'),
                DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
                DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
                DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
                DB::raw('MIN(t1.kota_kelahiran) AS kota_kelahiran'),
                DB::raw('MIN(t1.alamat_penerima) AS alamat_penerima'),
                DB::raw('MIN(t1.telepon_penerima) AS telepon_penerima'),
                DB::raw('MIN(t1.negara_pengirim) AS negara_pengirim'),
                DB::raw('MIN(t1.kode_pos_penerima) AS kode_pos_penerima')
            )
            ->leftJoin('transaksi_valid as t2', function ($join) use ($tanggal_dari) {
                $join->on('t1.nama_penerima', '=', 't2.nama_penerima')
                    ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
            })
            ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
            ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
            ->whereNull('t2.nama_penerima')
            ->groupBy('t1.nama_penerima')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('idpjk', fn() => '1003780')
                ->addColumn('kode_nasabah', fn() => '1')
                ->addColumn('identitas_lain', fn() => '-')
                ->addColumn('npwp', fn() => '-')
                ->addColumn('local_id', fn() => '-')
                ->make(true);
        }

        return view('checking.newcustomer_new', [
            'tanggal_sampai' => $tanggal_sampai,
            'tanggal_dari' => $tanggal_dari,
            'start_date' => $tanggal_dari,
            'end_date' => $tanggal_sampai,
            'data' => $query,
            'send' => ' ',
        ]);
    }

    public function search_newcustomer_all(Request $request)
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $send = "ya";
            try {
                [$tanggal_dari, $tanggal_sampai] = $this->resolveStartEndDates($request);
            } catch (InvalidArgumentException $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }

            // Ambil tahun dari tanggal_sampai
            $tahun_sampai = Carbon::createFromFormat('Y-m-d', $tanggal_sampai)->year;

            $quer = DB::table('transaksi_send_pos as t1')
                ->select(
                    DB::raw('MIN(t1.no) AS no'),
                    't1.nama_pengirim',
                    DB::raw('MIN(t1.no_referensi) AS no_referensi'),
                    DB::raw('MIN(t1.tanggal) AS tanggal'),
                    DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
                    DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
                    DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
                    DB::raw('MIN(t1.kota_lahir_pengirim) AS kota_lahir_pengirim'),
                    DB::raw('MIN(t1.alamat_pengirim) AS alamat_pengirim'),
                    DB::raw('MIN(t1.telpon_pengirim) AS telpon_pengirim'),
                    DB::raw('MIN(t1.negara_penerima) AS negara_penerima'),
                    DB::raw('MIN(t1.kode_pos_domisili_pengirim) AS kode_pos_domisili_pengirim')
                )
                ->leftJoin('transaksi_send_pos as t2', function ($join) use ($tanggal_dari) {
                    $join->on('t1.nama_pengirim', '=', 't2.nama_pengirim')
                        ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
                })
                ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
                ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
                ->whereNull('t2.nama_pengirim') // Filter nama_pengirim yang tidak ada di t2
                ->groupBy('t1.nama_pengirim')
                ->get();


            $query = DB::table('transaksi_valid as t1')
                ->select(
                    DB::raw('MIN(t1.no) AS no'),
                    't1.nama_penerima',
                    DB::raw('MIN(t1.no_referensi) AS no_referensi'),
                    DB::raw('MIN(t1.tanggal) AS tanggal'),
                    DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
                    DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
                    DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
                    DB::raw('MIN(t1.kota_kelahiran) AS kota_kelahiran'),
                    DB::raw('MIN(t1.alamat_penerima) AS alamat_penerima'),
                    DB::raw('MIN(t1.telepon_penerima) AS telepon_penerima'),
                    DB::raw('MIN(t1.negara_pengirim) AS negara_pengirim'),
                    DB::raw('MIN(t1.kode_pos_penerima) AS kode_pos_penerima')
                )
                ->leftJoin('transaksi_valid as t2', function ($join) use ($tanggal_dari) {
                    $join->on('t1.nama_penerima', '=', 't2.nama_penerima')
                        ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
                })
                ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
                ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
                ->whereNull('t2.nama_penerima') // Filter nama_pengirim yang tidak ada di t2
                ->groupBy('t1.nama_penerima')
                ->get();
            return view('checking.newcustomerall', [
                'tanggal_sampai' => $tanggal_sampai,
                'tanggal_dari' => $tanggal_dari,
                'start_date' => $tanggal_dari,
                'end_date' => $tanggal_sampai,
                'data' => $quer,
                'datarcv' => $query,
                'send' => $send
            ]);


        }

        return redirect('login')->with('You dont have access');
    }

    /*
    public function search_newcustomer(Request $request)
    {
        if(Auth::check()){
              $send="ya";
              $tanggal = $request->reservation; // Misalnya "09/01/2024 - 09/30/2024"
              $dari = explode(' - ', $tanggal);
              $tanggal_dari = Carbon::createFromFormat('m/d/Y', trim($dari[0]))->format('Y-m-d');
              $tanggal_sampai = Carbon::createFromFormat('m/d/Y', trim($dari[1]))->format('Y-m-d');

              // Ambil tahun dari tanggal_sampai
              $tahun_sampai = Carbon::createFromFormat('Y-m-d', $tanggal_sampai)->year;

             $quer = DB::table('transaksi_valid as t1')
              ->select(
                  DB::raw('MIN(t1.no) AS no'),
                  't1.nama_penerima',
                  DB::raw('MIN(t1.no_referensi) AS no_referensi'),
                  DB::raw('MIN(t1.tanggal) AS tanggal'),
                  DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
                  DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
                  DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
                  DB::raw('MIN(t1.kota_kelahiran) AS kota_kelahiran'),
                  DB::raw('MIN(t1.alamat_penerima) AS alamat_penerima'),
                  DB::raw('MIN(t1.telepon_penerima) AS telepon_penerima'),
                  DB::raw('MIN(t1.negara_pengirim) AS negara_pengirim'),
                  DB::raw('MIN(t1.kode_pos_penerima) AS kode_pos_penerima')
              )
              ->leftJoin('transaksi_send_pos as t2', function($join) use ($tanggal_dari) {
                  $join->on('t1.nama_penerima', '=', 't2.nama_penerima')
                        ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
              })
              ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
              ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
              ->whereNull('t2.nama_penerima') // Filter nama_pengirim yang tidak ada di t2
              ->groupBy('t1.nama_penerima')
              ->get();


              return view('checking.newcustomer_new',[
                'tanggal_sampai'=>$tanggal_sampai,'tanggal_dari'=>$tanggal_dari,'data'=>$quer
              ]);


            }

            return redirect('login')->with('You dont have access');
    }
    */

    public function search_newcustomer_send(Request $request)
    {
        //  cek berhasil login 
        if (Auth::check()) {
            $send = "ya";
            try {
                [$tanggal_dari, $tanggal_sampai] = $this->resolveStartEndDates($request);
            } catch (InvalidArgumentException $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }

            // Ambil tahun dari tanggal_sampai
            $tahun_sampai = Carbon::createFromFormat('Y-m-d', $tanggal_sampai)->year;

            $quer = DB::table('transaksi_send_pos as t1')
                ->select(
                    DB::raw('MIN(t1.no) AS no'),
                    't1.nama_pengirim',
                    DB::raw('MIN(t1.no_referensi) AS no_referensi'),
                    DB::raw('MIN(t1.tanggal) AS tanggal'),
                    DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
                    DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
                    DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
                    DB::raw('MIN(t1.kota_lahir_pengirim) AS kota_lahir_pengirim'),
                    DB::raw('MIN(t1.alamat_pengirim) AS alamat_pengirim'),
                    DB::raw('MIN(t1.telpon_pengirim) AS telpon_pengirim'),
                    DB::raw('MIN(t1.negara_penerima) AS negara_penerima'),
                    DB::raw('MIN(t1.kode_pos_domisili_pengirim) AS kode_pos_domisili_pengirim')
                )
                ->leftJoin('transaksi_send_pos as t2', function ($join) use ($tanggal_dari) {
                    $join->on('t1.nama_pengirim', '=', 't2.nama_pengirim')
                        ->where('t2.tanggal', '<=', $tanggal_dari . ' 23:59:59');
                })
                ->where('t1.tanggal', '>=', $tanggal_dari . ' 00:00:00')
                ->where('t1.tanggal', '<=', $tanggal_sampai . ' 23:59:59')
                ->whereNull('t2.nama_pengirim') // Filter nama_pengirim yang tidak ada di t2
                ->groupBy('t1.nama_pengirim')
                ->get();



            return view('checking.newcustomersend', [
                'tanggal_sampai' => $tanggal_sampai,
                'tanggal_dari' => $tanggal_dari,
                'start_date' => $tanggal_dari,
                'end_date' => $tanggal_sampai,
                'data' => $quer,
                'send' => $send
            ]);


        }

        return redirect('login')->with('You dont have access');
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

        $customerName = $transactions->first()?->nama_pengirim ?? '-';

        return view('checking.counting_customer_send_detail', compact(
            'transactions',
            'nomorIdentitas',
            'customerName'
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
                'start_date.required' => 'Start date wajib diisi.',
                'start_date.date_format' => 'Format start date tidak valid.',
                'end_date.required' => 'End date wajib diisi.',
                'end_date.date_format' => 'Format end date tidak valid.',
                'end_date.after_or_equal' => 'End date harus sama dengan atau setelah start date.',
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
                throw new InvalidArgumentException('Format tanggal tidak valid.');
            }

            if ($end->lt($start)) {
                throw new InvalidArgumentException('End date tidak boleh lebih kecil dari start date.');
            }

            return [$start->toDateString(), $end->toDateString()];
        }

        $reservation = str_replace('+-+', ' - ', (string) $request->input('reservation', ''));
        if (!str_contains($reservation, ' - ')) {
            throw new InvalidArgumentException('Start date dan end date wajib diisi.');
        }

        try {
            [$dari, $sampai] = explode(' - ', $reservation, 2);

            return [
                Carbon::createFromFormat('m/d/Y', trim($dari))->format('Y-m-d'),
                Carbon::createFromFormat('m/d/Y', trim($sampai))->format('Y-m-d'),
            ];
        } catch (\Throwable $e) {
            throw new InvalidArgumentException('Format tanggal tidak valid.');
        }
    }

}


