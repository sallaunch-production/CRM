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
Route::post('logout',[App\Http\Controllers\AuthController::class,'logout'])->name('logout');
Route::post('verify',[App\Http\Controllers\AuthController::class,'verify'])->name('proses.verify');

Route::group(['middleware' => ['auth','leveluser:reporting']], function () {
    //HomeController
Route::get('dashboard',[App\Http\Controllers\HomeController::class,'dashboard'])->name('dashboard');
Route::get('transaksi_receive_pos',[App\Http\Controllers\TransactionalController::class,'transaksi_receive_pos'])->name('transaksi_receive_pos');
Route::get('transaksi_send_pos',[App\Http\Controllers\TransactionalController::class,'transaksi_send_pos'])->name('transaksi_send_pos');
Route::get('nopend',[App\Http\Controllers\NopendController::class,'nopend'])->name('nopend');
Route::get('agent_sort',[App\Http\Controllers\NopendController::class,'agent_sort'])->name('agent_sort');
Route::post('cari_nopend',[App\Http\Controllers\NopendController::class,'cari_nopend'])->name('cari_nopend');
Route::post('cari_agent',[App\Http\Controllers\NopendController::class,'cari_agent'])->name('cari_agent');
Route::post('cari_agent_belumaktif',[App\Http\Controllers\NopendController::class,'cari_agent_belumaktif'])->name('cari_agent_belumaktif');
Route::get('history_refund',[App\Http\Controllers\ReportingController::class,'history_refund'])->name('history_refund');
Route::get('history_refund_pos',[App\Http\Controllers\ReportingController::class,'history_refund_pos'])->name('history_refund_pos');
Route::get('history_amend_pos',[App\Http\Controllers\ReportingController::class,'history_amend_pos'])->name('history_amend_pos');
Route::get('popimage',[App\Http\Controllers\ReportingController::class,'popimage'])->name('popimage');
Route::get('nama',[App\Http\Controllers\CheckingController::class,'nama'])->name('nama');
Route::post('cari_nama',[App\Http\Controllers\CheckingController::class,'cari_nama'])->name('cari_nama');
Route::get('profil',[App\Http\Controllers\CheckingController::class,'profil'])->name('profil');
Route::post('search_profil',[App\Http\Controllers\CheckingController::class,'search_profil'])->name('search_profil');
Route::get('sales',[App\Http\Controllers\CheckingController::class,'sales'])->name('sales');
Route::get('newcustomer',[App\Http\Controllers\CheckingController::class,'newcustomer'])->name('newcustomer');
Route::get('sanction',[App\Http\Controllers\CheckingController::class,'sanction'])->name('sanction');
Route::get('filtering',[App\Http\Controllers\CheckingController::class,'filtering'])->name('filtering');
Route::post('cari_filtering',[App\Http\Controllers\CheckingController::class,'cari_filtering'])->name('cari_filtering');
Route::get('list_country',[App\Http\Controllers\ReportingController::class,'list_country'])->name('list_country');
Route::get('negara',[App\Http\Controllers\ReportingController::class,'negara'])->name('negara');
Route::get('tambah_list_country',[App\Http\Controllers\ReportingController::class,'tambah_list_country'])->name('tambah_list_country');
Route::post('add_list_country',[App\Http\Controllers\ReportingController::class,'add_list_country'])->name('add_list_country');
Route::post('delete_list_country',[App\Http\Controllers\ReportingController::class,'delete_list_country'])->name('delete_list_country');
Route::get('filtering_transaction',[App\Http\Controllers\ReportingController::class,'filtering_transaction'])->name('filtering_transaction');
Route::post('search_filtering_transaction',[App\Http\Controllers\ReportingController::class,'search_filtering_transaction'])->name('search_filtering_transaction');
Route::post('cetak_laporan_bi_baru',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_baru'])->name('cetak_laporan_bi_baru');
Route::post('cetak_laporan_bi_g0001',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_g0001'])->name('cetak_laporan_bi_g0001');
Route::post('cetak_laporan_bi_g0002',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_g0002'])->name('cetak_laporan_bi_g0002');
Route::post('cetak_laporan_bi_baru_txt',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_baru_txt'])->name('cetak_laporan_bi_baru_txt');

Route::post('cari_laporan',[App\Http\Controllers\ReportingController::class,'cari_laporan'])->name('cari_laporan');
Route::get('laporan',[App\Http\Controllers\ReportingController::class,'laporan'])->name('laporan');
Route::get('kota',[App\Http\Controllers\ReportingController::class,'kota'])->name('kota');
Route::post('search_sales',[App\Http\Controllers\CheckingController::class,'search_sales'])->name('search_sales');
Route::post('search_newcustomer',[App\Http\Controllers\CheckingController::class,'search_newcustomer'])->name('search_newcustomer');
Route::get('screening',[App\Http\Controllers\CheckingController::class,'screening'])->name('screening');
Route::post('cari_screening',[App\Http\Controllers\CheckingController::class,'cari_screening'])->name('cari_screening');

});

Route::group(['middleware' => ['auth','leveluser:operasional,reporting']], function () {
    //ReportingController
Route::get('transaksi_direct',[App\Http\Controllers\ReportingController::class,'transaksi_direct'])->name('transaksi_direct');
    //HomeController
Route::get('dashboard',[App\Http\Controllers\HomeController::class,'dashboard'])->name('dashboard');
});