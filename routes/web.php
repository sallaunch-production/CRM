<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
*/
// Auth::routes();
Route::get('/', function () {
    return view('auth.login');
});
Route::get('login',[App\Http\Controllers\AuthController::class,'index'])->name('login');
Route::get('validation',[App\Http\Controllers\AuthController::class,'validation'])->name('validation');
Route::get('register_barcode',[App\Http\Controllers\AuthController::class,'register_barcode'])->name('register_barcode');

Route::get('register',[App\Http\Controllers\AuthController::class,'register'])->name('register');
Route::get('erro_page',[App\Http\Controllers\AuthController::class,'erro_page'])->name('erro_page');
Route::post('proses_login',[App\Http\Controllers\AuthController::class,'proses_login'])->name('proses.login');
Route::post('proses_register',[App\Http\Controllers\AuthController::class,'proses_register'])->name('proses.register');
Route::post('logout',[App\Http\Controllers\AuthController::class,'logout'])->name('logout')->middleware('auth');

Route::post('verify',[App\Http\Controllers\AuthController::class,'verify'])->name('proses.verify');

Route::group(['middleware' => ['direktur']], function () {  //HomeController
Route::get('dashboard',[App\Http\Controllers\HomeController::class,'dashboard'])->name('dashboard')->middleware('auth');
Route::get('transaksi_receive_pos',[App\Http\Controllers\TransactionalController::class,'transaksi_receive_pos'])->name('transaksi_receive_pos')->middleware('auth');
Route::get('transaksi_send_pos',[App\Http\Controllers\TransactionalController::class,'transaksi_send_pos'])->name('transaksi_send_pos')->middleware('auth');
Route::get('nopend',[App\Http\Controllers\NopendController::class,'nopend'])->name('nopend')->middleware('auth');
Route::get('agent_sort',[App\Http\Controllers\NopendController::class,'agent_sort'])->name('agent_sort')->middleware('auth');
Route::post('cari_nopend',[App\Http\Controllers\NopendController::class,'cari_nopend'])->name('cari_nopend')->middleware('auth');
Route::post('cari_agent',[App\Http\Controllers\NopendController::class,'cari_agent'])->name('cari_agent')->middleware('auth');
Route::post('cari_agent_belumaktif',[App\Http\Controllers\NopendController::class,'cari_agent_belumaktif'])->name('cari_agent_belumaktif')->middleware('auth');
Route::get('history_refund',[App\Http\Controllers\ReportingController::class,'history_refund'])->name('history_refund')->middleware('auth');
Route::get('history_refund_pos',[App\Http\Controllers\ReportingController::class,'history_refund_pos'])->name('history_refund_pos')->middleware('auth');
Route::get('history_amend_pos',[App\Http\Controllers\ReportingController::class,'history_amend_pos'])->name('history_amend_pos')->middleware('auth');
Route::get('popimage',[App\Http\Controllers\ReportingController::class,'popimage'])->name('popimage')->middleware('auth');
Route::get('nama',[App\Http\Controllers\CheckingController::class,'nama'])->name('nama')->middleware('auth');
Route::post('cari_nama',[App\Http\Controllers\CheckingController::class,'cari_nama'])->name('cari_nama')->middleware('auth');
Route::get('profil',[App\Http\Controllers\CheckingController::class,'profil'])->name('profil')->middleware('auth');
Route::post('search_profil',[App\Http\Controllers\CheckingController::class,'search_profil'])->name('search_profil')->middleware('auth');
Route::get('sales',[App\Http\Controllers\CheckingController::class,'sales'])->name('sales')->middleware('auth');
Route::get('newcustomer',[App\Http\Controllers\CheckingController::class,'newcustomer'])->name('newcustomer')->middleware('auth');
Route::get('sanction',[App\Http\Controllers\CheckingController::class,'sanction'])->name('sanction')->middleware('auth');
Route::get('filtering',[App\Http\Controllers\CheckingController::class,'filtering'])->name('filtering')->middleware('auth');
Route::post('cari_filtering',[App\Http\Controllers\CheckingController::class,'cari_filtering'])->name('cari_filtering')->middleware('auth');
Route::get('list_country',[App\Http\Controllers\ReportingController::class,'list_country'])->name('list_country')->middleware('auth');
Route::get('negara',[App\Http\Controllers\ReportingController::class,'negara'])->name('negara')->middleware('auth');
Route::get('tambah_list_country',[App\Http\Controllers\ReportingController::class,'tambah_list_country'])->name('tambah_list_country')->middleware('auth');
Route::post('add_list_country',[App\Http\Controllers\ReportingController::class,'add_list_country'])->name('add_list_country')->middleware('auth');
Route::post('delete_list_country',[App\Http\Controllers\ReportingController::class,'delete_list_country'])->name('delete_list_country')->middleware('auth');
Route::get('filtering_transaction',[App\Http\Controllers\ReportingController::class,'filtering_transaction'])->name('filtering_transaction')->middleware('auth');
Route::get('filtering_identitas',[App\Http\Controllers\ReportingController::class,'filtering_identitas'])->name('filtering_identitas')->middleware('auth');
Route::get('filter_telepon',[App\Http\Controllers\ReportingController::class,'filter_telepon'])->name('filter_telepon')->middleware('auth');
Route::get('filter_negara',[App\Http\Controllers\ReportingController::class,'filter_negara'])->name('filter_negara')->middleware('auth');
Route::get('filter_sender',[App\Http\Controllers\ReportingController::class,'filter_sender'])->name('filter_sender')->middleware('auth');
Route::post('search_filtering_transaction',[App\Http\Controllers\ReportingController::class,'search_filtering_transaction'])->name('search_filtering_transaction')->middleware('auth');
Route::post('search_filtering_identitas',[App\Http\Controllers\ReportingController::class,'search_filtering_identitas'])->name('search_filtering_identitas')->middleware('auth');
Route::post('detail_identitas',[App\Http\Controllers\ReportingController::class,'detail_identitas'])->name('detail_identitas')->middleware('auth');
Route::post('search_filtering_telepon',[App\Http\Controllers\ReportingController::class,'search_filtering_telepon'])->name('search_filtering_telepon')->middleware('auth');
Route::post('detail_telpon',[App\Http\Controllers\ReportingController::class,'detail_telpon'])->name('detail_telpon')->middleware('auth');
Route::post('search_filtering_negara',[App\Http\Controllers\ReportingController::class,'search_filtering_negara'])->name('search_filtering_negara')->middleware('auth');
Route::post('detail_negara',[App\Http\Controllers\ReportingController::class,'detail_negara'])->name('detail_negara')->middleware('auth');
Route::post('search_filtering_sender',[App\Http\Controllers\ReportingController::class,'search_filtering_sender'])->name('search_filtering_sender')->middleware('auth');
Route::post('detail_sender',[App\Http\Controllers\ReportingController::class,'detail_sender'])->name('detail_sender')->middleware('auth');
Route::post('detail_kota',[App\Http\Controllers\ReportingController::class,'detail_kota'])->name('detail_kota')->middleware('auth');
Route::post('cari_kota',[App\Http\Controllers\ReportingController::class,'cari_kota'])->name('cari_kota')->middleware('auth');
Route::post('tanggal_valid',[App\Http\Controllers\ReportingController::class,'tanggal_valid'])->name('tanggal_valid')->middleware('auth');
Route::post('proses_detail_negara',[App\Http\Controllers\ReportingController::class,'proses_detail_negara'])->name('proses_detail_negara')->middleware('auth');
Route::post('cari_negara',[App\Http\Controllers\ReportingController::class,'cari_negara'])->name('cari_negara')->middleware('auth');
Route::post('pencarian_detail_negara',[App\Http\Controllers\ReportingController::class,'pencarian_detail_negara'])->name('pencarian_detail_negara')->middleware('auth');

Route::post('cetak_laporan_bi_baru',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_baru'])->name('cetak_laporan_bi_baru')->middleware('auth');
Route::post('cetak_laporan_bi_g0001',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_g0001'])->name('cetak_laporan_bi_g0001')->middleware('auth');
Route::post('cetak_laporan_bi_g0003',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_g0003'])->name('cetak_laporan_bi_g0003')->middleware('auth');
Route::post('cetak_laporan_bi_baru_txt',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_baru_txt'])->name('cetak_laporan_bi_baru_txt')->middleware('auth');
Route::post('cetak_laporan_ppatk_baru',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_ppatk_baru'])->name('cetak_laporan_ppatk_baru')->middleware('auth');

Route::post('cari_laporan',[App\Http\Controllers\ReportingController::class,'cari_laporan'])->name('cari_laporan')->middleware('auth');
Route::get('laporan',[App\Http\Controllers\ReportingController::class,'laporan'])->name('laporan')->middleware('auth');
Route::get('kota',[App\Http\Controllers\ReportingController::class,'kota'])->name('kota')->middleware('auth');
Route::post('search_sales',[App\Http\Controllers\CheckingController::class,'search_sales'])->name('search_sales')->middleware('auth');
Route::post('search_newcustomer',[App\Http\Controllers\CheckingController::class,'search_newcustomer'])->name('search_newcustomer')->middleware('auth');
Route::get('screening',[App\Http\Controllers\CheckingController::class,'screening'])->name('screening')->middleware('auth');
Route::post('cari_screening',[App\Http\Controllers\CheckingController::class,'cari_screening'])->name('cari_screening')->middleware('auth');

});
