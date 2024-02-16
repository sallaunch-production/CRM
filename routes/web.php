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
Route::get('transaksi',[App\Http\Controllers\TransactionalController::class,'transaksi'])->name('transaksi');
Route::get('transaksi_send_pos',[App\Http\Controllers\TransactionalController::class,'transaksi_send_pos'])->name('transaksi_send_pos');
Route::get('nopend',[App\Http\Controllers\NopendController::class,'nopend'])->name('nopend');
Route::get('agent_sort',[App\Http\Controllers\NopendController::class,'agent_sort'])->name('agent_sort');
Route::post('cari_nopend',[App\Http\Controllers\NopendController::class,'cari_nopend'])->name('cari_nopend');
Route::post('cari_agent',[App\Http\Controllers\NopendController::class,'cari_agent'])->name('cari_agent');
Route::post('cari_agent_belumaktif',[App\Http\Controllers\NopendController::class,'cari_agent_belumaktif'])->name('cari_agent_belumaktif');



});

Route::group(['middleware' => ['auth','leveluser:operator,admin']], function () {
    //ReportingController
Route::get('transaksi_direct',[App\Http\Controllers\ReportingController::class,'transaksi_direct'])->name('transaksi_direct');
    //HomeController
Route::get('dashboard',[App\Http\Controllers\HomeController::class,'dashboard'])->name('dashboard');
});