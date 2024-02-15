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
Route::get('register',[App\Http\Controllers\AuthController::class,'register'])->name('register');
Route::get('erro_page',[App\Http\Controllers\AuthController::class,'erro_page'])->name('erro_page');
Route::post('proses_login',[App\Http\Controllers\AuthController::class,'proses_login'])->name('proses.login');
Route::post('proses_register',[App\Http\Controllers\AuthController::class,'proses_register'])->name('proses.register');
Route::post('logout',[App\Http\Controllers\AuthController::class,'logout'])->name('logout');

Route::group(['middleware' => ['auth','leveluser:admin']], function () {
    //HomeController
Route::get('dashboard',[App\Http\Controllers\HomeController::class,'dashboard'])->name('dashboard');
Route::get('transaksi',[App\Http\Controllers\HomeController::class,'transaksi'])->name('transaksi');
Route::post('transaksi_search',[App\Http\Controllers\HomeController::class,'transaksi_search'])->name('transaksi_search');
    //ReportingController
Route::get('transaksi_direct',[App\Http\Controllers\ReportingController::class,'transaksi_direct'])->name('transaksi_direct');
Route::get('transaksi_send',[App\Http\Controllers\ReportingController::class,'transaksi_send'])->name('transaksi_send');
Route::get('transaksi_compare',[App\Http\Controllers\ReportingController::class,'transaksi_compare'])->name('transaksi_compare');
Route::get('transaksi_compare_send',[App\Http\Controllers\ReportingController::class,'transaksi_compare_send'])->name('transaksi_compare_send');
Route::get('direct_report',[App\Http\Controllers\ReportingController::class,'direct_report'])->name('direct_report');
Route::get('report_pos',[App\Http\Controllers\ReportingController::class,'report_pos'])->name('report_pos');
Route::get('mto_report',[App\Http\Controllers\ReportingController::class,'mto_report'])->name('mto_report');
Route::get('report_send_invoice',[App\Http\Controllers\ReportingController::class,'report_send_invoice'])->name('report_send_invoice');
Route::get('rekons',[App\Http\Controllers\ReportingController::class,'rekons'])->name('rekons');
Route::get('rekonssend',[App\Http\Controllers\ReportingController::class,'rekonssend'])->name('rekonssend');
Route::post('send_invoice_search',[App\Http\Controllers\ReportingController::class,'send_invoice_search'])->name('send_invoice_search');
Route::post('search_compare_receive',[App\Http\Controllers\ReportingController::class,'search_compare_receive'])->name('search_compare_receive');
Route::post('search_compare_send',[App\Http\Controllers\ReportingController::class,'search_compare_send'])->name('search_compare_send');
Route::post('direct_report_receive',[App\Http\Controllers\ReportingController::class,'direct_report_receive'])->name('direct_report_receive');
Route::post('direct_report_send',[App\Http\Controllers\ReportingController::class,'direct_report_send'])->name('direct_report_send');
Route::post('direct_search_receive',[App\Http\Controllers\ReportingController::class,'direct_search_receive'])->name('direct_search_receive');
Route::post('direct_search_send',[App\Http\Controllers\ReportingController::class,'direct_search_send'])->name('direct_search_send');
Route::post('mto_moneygram',[App\Http\Controllers\ReportingController::class,'mto_moneygram'])->name('mto_moneygram');
Route::post('mto_sigue',[App\Http\Controllers\ReportingController::class,'mto_sigue'])->name('mto_sigue');
Route::post('mto_moneygram_search',[App\Http\Controllers\ReportingController::class,'mto_moneygram_search'])->name('mto_moneygram_search');
Route::post('mto_sigue_search',[App\Http\Controllers\ReportingController::class,'mto_sigue_search'])->name('mto_sigue_search');
Route::post('report_pos_search',[App\Http\Controllers\ReportingController::class,'report_pos_search'])->name('report_pos_search');
Route::post('csvpilot',[App\Http\Controllers\ImportExportController::class,'csvpilot'])->name('csvpilot');
Route::post('cetak_invoce_search',[App\Http\Controllers\ImportExportController::class,'cetak_invoce_search'])->name('cetak_invoce_search');


    //CheckingController
Route::get('transaksi_anomali',[App\Http\Controllers\CheckingController::class,'transaksi_anomali'])->name('transaksi_anomali');
Route::post('anomali_destroy',[App\Http\Controllers\CheckingController::class,'anomali_destroy'])->name('anomali_destroy');
Route::get('error_test',[App\Http\Controllers\CheckingController::class,'error_test'])->name('error_test');
Route::get('viewing',[App\Http\Controllers\CheckingController::class,'viewing'])->name('viewing');
Route::get('request_setor',[App\Http\Controllers\CheckingController::class,'request_setor'])->name('request_setor');
Route::get('search',[App\Http\Controllers\CheckingController::class,'search'])->name('search');
Route::post('search_print',[App\Http\Controllers\CheckingController::class,'search_print'])->name('search_print');
Route::post('agent_copy',[App\Http\Controllers\CheckingController::class,'agent_copy'])->name('agent_copy');
Route::get('cek-status',[App\Http\Controllers\CheckingController::class,'cek_status'])->name('cek_status');
Route::post('search_viewing',[App\Http\Controllers\CheckingController::class,'search_viewing'])->name('search_viewing');
 //Transactional
Route::get('amend',[App\Http\Controllers\TransactionalController::class,'amend'])->name('amend');
Route::get('amend_pos',[App\Http\Controllers\TransactionalController::class,'amend_pos'])->name('amend_pos');
Route::get('sendreversal',[App\Http\Controllers\TransactionalController::class,'send_reversal'])->name('send_reversal');
Route::get('sendreversalpos',[App\Http\Controllers\TransactionalController::class,'send_reversalpos'])->name('send_reversalpos');
    //EntryController
Route::get('saldo',[App\Http\Controllers\EntryController::class,'saldo'])->name('saldo');
Route::post('saldo_destroy',[App\Http\Controllers\EntryController::class,'saldo_destroy'])->name('saldo_destroy');
Route::post('store_edit_saldo',[App\Http\Controllers\EntryController::class,'store_edit_saldo'])->name('store_edit_saldo');
Route::post('edit_saldo',[App\Http\Controllers\EntryController::class,'edit_saldo'])->name('edit_saldo');
Route::post('add_saldo',[App\Http\Controllers\EntryController::class,'add_saldo'])->name('add_saldo');
Route::get('transaksi_send_pos',[App\Http\Controllers\ReportingController::class,'transaksi_send_pos'])->name('transaksi_send_pos');
Route::get('reject',[App\Http\Controllers\EntryController::class,'reject'])->name('reject');
Route::get('blocking',[App\Http\Controllers\EntryController::class,'blocking'])->name('blocking');
Route::post('negara_exception_add',[App\Http\Controllers\EntryController::class,'negara_exception_add'])->name('negara_exception_add');
Route::post('exception_destroy',[App\Http\Controllers\EntryController::class,'exception_destroy'])->name('exception_destroy');
});

Route::group(['middleware' => ['auth','leveluser:operator,admin']], function () {
    //ReportingController
Route::get('transaksi_direct',[App\Http\Controllers\ReportingController::class,'transaksi_direct'])->name('transaksi_direct');
    //HomeController
Route::get('dashboard',[App\Http\Controllers\HomeController::class,'dashboard'])->name('dashboard');
Route::get('transaksi',[App\Http\Controllers\HomeController::class,'transaksi'])->name('transaksi');
Route::post('transaksi_search',[App\Http\Controllers\HomeController::class,'transaksi_search'])->name('transaksi_search');
});