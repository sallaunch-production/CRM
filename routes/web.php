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

*/
// Auth::routes();
Route::get('/', function () {
    return view('auth.login');
});
Route::get('login',[App\Http\Controllers\AuthController::class,'index'])->name('login')->middleware('guest');
Route::get('validation',[App\Http\Controllers\AuthController::class,'validation'])->name('validation');
Route::get('register_barcode',[App\Http\Controllers\AuthController::class,'register_barcode'])->name('register_barcode');

Route::get('register',[App\Http\Controllers\AuthController::class,'register'])->name('register');
Route::get('erro_page',[App\Http\Controllers\AuthController::class,'erro_page'])->name('erro_page');
Route::post('proses_login',[App\Http\Controllers\AuthController::class,'proses_login'])->name('proses.login');
Route::post('proses_register',[App\Http\Controllers\AuthController::class,'proses_register'])->name('proses.register');
Route::post('logout',[App\Http\Controllers\AuthController::class,'logout'])->name('logout')->middleware('auth');

Route::post('verify',[App\Http\Controllers\AuthController::class,'verify'])->name('proses.verify');

Route::group(['middleware' => ['compliance']], function () {  //HomeController
Route::get('dashboard',[App\Http\Controllers\HomeController::class,'dashboard'])->name('dashboard')->middleware('auth');
Route::get('transaksi_receive_pos',[App\Http\Controllers\TransactionalController::class,'transaksi_receive_pos'])->name('transaksi_receive_pos')->middleware('auth');
Route::get('transaksi_send_pos',[App\Http\Controllers\TransactionalController::class,'transaksi_send_pos'])->name('transaksi_send_pos')->middleware('auth');
Route::get('nopend',[App\Http\Controllers\NopendController::class,'nopend'])->name('nopend')->middleware('auth');
Route::get('agent_sort',[App\Http\Controllers\NopendController::class,'agent_sort'])->name('agent_sort')->middleware('auth');
Route::post('cari_nopend',[App\Http\Controllers\NopendController::class,'cari_nopend'])->name('cari_nopend')->middleware('auth');
Route::post('cari_agent',[App\Http\Controllers\NopendController::class,'cari_agent'])->name('cari_agent')->middleware('auth');
Route::post('liat_agent_belum_aktif',[App\Http\Controllers\NopendController::class,'liat_agent_belum_aktif'])->name('liat_agent_belum_aktif')->middleware('auth');
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
Route::get('negara_send',[App\Http\Controllers\ReportingController::class,'negara_send'])->name('negara_send')->middleware('auth');
Route::get('negara_receive',[App\Http\Controllers\ReportingController::class,'negara_receive'])->name('negara_receive')->middleware('auth');
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
Route::post('proses_detail_negara_send',[App\Http\Controllers\ReportingController::class,'proses_detail_negara_send'])->name('proses_detail_negara_send')->middleware('auth');
Route::post('cari_negara',[App\Http\Controllers\ReportingController::class,'cari_negara'])->name('cari_negara')->middleware('auth');
Route::post('cari_negara_send',[App\Http\Controllers\ReportingController::class,'cari_negara_send'])->name('cari_negara_send')->middleware('auth');
Route::post('pencarian_detail_negara',[App\Http\Controllers\ReportingController::class,'pencarian_detail_negara'])->name('pencarian_detail_negara')->middleware('auth');
Route::post('pencarian_detail_negara_send',[App\Http\Controllers\ReportingController::class,'pencarian_detail_negara_send'])->name('pencarian_detail_negara_send')->middleware('auth');

Route::post('cetak_laporan_bi_baru',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_baru'])->name('cetak_laporan_bi_baru')->middleware('auth');
Route::post('cetak_laporan_bi_g0001',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_g0001'])->name('cetak_laporan_bi_g0001')->middleware('auth');
Route::post('cetak_laporan_bi_g0003',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_g0003'])->name('cetak_laporan_bi_g0003')->middleware('auth');
Route::post('cetak_laporan_bi_baru_txt',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_bi_baru_txt'])->name('cetak_laporan_bi_baru_txt')->middleware('auth');
Route::post('cetak_laporan_ppatk_baru',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_ppatk_baru'])->name('cetak_laporan_ppatk_baru')->middleware('auth');
Route::post('cetak_laporan_ppatk_baru_c2a',[App\Http\Controllers\ImportExportController::class,'cetak_laporan_ppatk_baru_c2a'])->name('cetak_laporan_ppatk_baru_c2a')->middleware('auth');

Route::post('cari_laporan',[App\Http\Controllers\ReportingController::class,'cari_laporan'])->name('cari_laporan')->middleware('auth');
Route::get('laporan',[App\Http\Controllers\ReportingController::class,'laporan'])->name('laporan')->middleware('auth');
Route::get('kota',[App\Http\Controllers\ReportingController::class,'kota'])->name('kota')->middleware('auth');
Route::post('search_sales',[App\Http\Controllers\CheckingController::class,'search_sales'])->name('search_sales')->middleware('auth');
Route::post('search_newcustomer',[App\Http\Controllers\CheckingController::class,'search_newcustomer'])->name('search_newcustomer')->middleware('auth');
Route::get('screening',[App\Http\Controllers\CheckingController::class,'screening'])->name('screening')->middleware('auth');
Route::post('cari_screening',[App\Http\Controllers\CheckingController::class,'cari_screening'])->name('cari_screening')->middleware('auth');
Route::post('ubah_password',[App\Http\Controllers\HomeController::class,'ubah_password'])->name('ubah_password')->middleware('auth');
Route::get('settings_user',[App\Http\Controllers\HomeController::class,'settings_user'])->name('settings_user')->middleware('auth');
Route::get('screening_transaksi',[App\Http\Controllers\ReportingController::class,'screening_transaksi'])->name('screening_transaksi')->middleware('auth');
Route::post('cari_screening_transaksi',[App\Http\Controllers\ReportingController::class,'cari_screening_transaksi'])->name('cari_screening_transaksi')->middleware('auth');
Route::post('page_history_refund',[App\Http\Controllers\ReportingController::class,'page_history_refund'])->name('page_history_refund')->middleware('auth');
Route::post('upload_history_refund',[App\Http\Controllers\ReportingController::class,'upload_history_refund'])->name('upload_history_refund')->middleware('auth');
Route::post('edit_history_refund',[App\Http\Controllers\ReportingController::class,'edit_history_refund'])->name('edit_history_refund')->middleware('auth');
Route::post('delete_history_refund',[App\Http\Controllers\ReportingController::class,'delete_history_refund'])->name('delete_history_refund')->middleware('auth');
Route::post('edit_upload_history_refund',[App\Http\Controllers\ReportingController::class,'edit_upload_history_refund'])->name('edit_upload_history_refund')->middleware('auth');
Route::post('upload_refund_pos',[App\Http\Controllers\ReportingController::class,'upload_refund_pos'])->name('upload_refund_pos')->middleware('auth');
Route::post('act_upload_refund_pos',[App\Http\Controllers\ReportingController::class,'act_upload_refund_pos'])->name('act_upload_refund_pos')->middleware('auth');
Route::post('edit_refund_pos',[App\Http\Controllers\ReportingController::class,'edit_refund_pos'])->name('edit_refund_pos')->middleware('auth');
Route::post('act_edit_refund_pos',[App\Http\Controllers\ReportingController::class,'act_edit_refund_pos'])->name('act_edit_refund_pos')->middleware('auth');
Route::post('delete_refund_pos',[App\Http\Controllers\ReportingController::class,'delete_refund_pos'])->name('delete_refund_pos')->middleware('auth');
Route::post('upload_pop',[App\Http\Controllers\ReportingController::class,'upload_pop'])->name('upload_pop')->middleware('auth');
Route::post('act_upload_pop',[App\Http\Controllers\ReportingController::class,'act_upload_pop'])->name('act_upload_pop')->middleware('auth');
Route::post('edit_popimages',[App\Http\Controllers\ReportingController::class,'edit_popimages'])->name('edit_popimages')->middleware('auth');
Route::post('delete_popimages',[App\Http\Controllers\ReportingController::class,'delete_popimages'])->name('delete_popimages')->middleware('auth');
Route::post('act_edit_popimage',[App\Http\Controllers\ReportingController::class,'act_edit_popimage'])->name('act_edit_popimage')->middleware('auth');
Route::post('tambah_sanction',[App\Http\Controllers\CheckingController::class,'tambah_sanction'])->name('tambah_sanction')->middleware('auth');
Route::post('act_tambah_sanction',[App\Http\Controllers\CheckingController::class,'act_tambah_sanction'])->name('act_tambah_sanction')->middleware('auth');

});



Route::group(['middleware' => ['operasional']], function () {
    Route::get('dashboard_operasional',[App\Http\Controllers\HomeController::class,'dashboard'])->name('dashboard_operasional')->middleware('auth');
    Route::get('list_country_operasional',[App\Http\Controllers\ReportingController::class,'list_country'])->name('list_country_operasional')->middleware('auth');
    Route::post('search_filtering_transaction_operasional',[App\Http\Controllers\ReportingController::class,'search_filtering_transaction'])->name('search_filtering_transaction_operasional')->middleware('auth');
    Route::post('search_filtering_identitas_operasional',[App\Http\Controllers\ReportingController::class,'search_filtering_identitas'])->name('search_filtering_identitas_operasional')->middleware('auth');
    Route::post('search_filtering_sender_operasional',[App\Http\Controllers\ReportingController::class,'search_filtering_sender'])->name('search_filtering_sender_operasional')->middleware('auth');
    Route::post('search_filtering_negara_operasional',[App\Http\Controllers\ReportingController::class,'search_filtering_negara'])->name('search_filtering_negara_operasional')->middleware('auth');
    Route::post('proses_detail_negara_operasional',[App\Http\Controllers\ReportingController::class,'proses_detail_negara'])->name('proses_detail_negara_operasional')->middleware('auth');
    Route::post('proses_detail_negara_operasional_send',[App\Http\Controllers\ReportingController::class,'proses_detail_negara'])->name('proses_detail_negara_operasional')->middleware('auth');
    Route::post('detail_kota_operasional',[App\Http\Controllers\ReportingController::class,'detail_kota'])->name('detail_kota_operasional')->middleware('auth');
    Route::post('detail_identitas_operasional',[App\Http\Controllers\ReportingController::class,'detail_identitas'])->name('detail_identitas_operasional')->middleware('auth');
    Route::get('settings',[App\Http\Controllers\HomeController::class,'settings_user'])->name('settings')->middleware('auth');
    Route::get('kota-operasional',[App\Http\Controllers\ReportingController::class,'kota'])->name('kota-operasional')->middleware('auth');
    Route::get('negara-operasional',[App\Http\Controllers\ReportingController::class,'negara'])->name('negara-operasional')->middleware('auth');
    Route::post('search_filtering_telepon_operasional',[App\Http\Controllers\ReportingController::class,'search_filtering_telepon'])->name('search_filtering_telepon_operasional')->middleware('auth');

    Route::post('ubah_password_operasional',[App\Http\Controllers\HomeController::class,'ubah_password'])->name('ubah_password_operasional')->middleware('auth');
    Route::get('filtering_transaction_operasional',[App\Http\Controllers\ReportingController::class,'filtering_transaction'])->name('filtering_transaction')->middleware('auth');
    Route::get('filtering_identitas_operasional',[App\Http\Controllers\ReportingController::class,'filtering_identitas'])->name('filtering_identitas')->middleware('auth');
    Route::get('filter_telepon_operasional',[App\Http\Controllers\ReportingController::class,'filter_telepon'])->name('filter_telepon')->middleware('auth');
    Route::get('filter_negara_operasional',[App\Http\Controllers\ReportingController::class,'filter_negara'])->name('filter_negara')->middleware('auth');
    Route::get('filter_sender_operasional',[App\Http\Controllers\ReportingController::class,'filter_sender'])->name('filter_sender')->middleware('auth');

    Route::get('filtering-operasional',[App\Http\Controllers\CheckingController::class,'filtering'])->name('filtering')->middleware('auth');
    Route::post('cari_filtering_operasional',[App\Http\Controllers\CheckingController::class,'cari_filtering'])->name('cari_filtering')->middleware('auth');
    
    Route::get('history_refund_operasional',[App\Http\Controllers\ReportingController::class,'history_refund'])->name('history_refund')->middleware('auth');
    Route::get('history_refund_pos_operasional',[App\Http\Controllers\ReportingController::class,'history_refund_pos'])->name('history_refund_pos')->middleware('auth');
    Route::get('history_amend_pos_operasional',[App\Http\Controllers\ReportingController::class,'history_amend_pos'])->name('history_amend_pos')->middleware('auth');
    
    Route::get('popimage-operasional',[App\Http\Controllers\ReportingController::class,'popimage'])->name('popimage')->middleware('auth');
    Route::get('transaksi_send_pos_operasional',[App\Http\Controllers\TransactionalController::class,'transaksi_send_pos_operasional'])->name('transaksi_send_pos_operasional')->middleware('auth');
    Route::get('transaksi_receive_pos_operasional',[App\Http\Controllers\TransactionalController::class,'transaksi_receive_pos_operasional'])->name('transaksi_receive_pos_operasional')->middleware('auth');
    Route::get('screening_transaksi_operasional',[App\Http\Controllers\ReportingController::class,'screening_transaksi'])->name('screening_transaksi')->middleware('auth');
    Route::post('cari_screening_transaksi_oprasional',[App\Http\Controllers\ReportingController::class,'cari_screening_transaksi'])->name('cari_screening_transaksi_oprasional')->middleware('auth');
    
});
