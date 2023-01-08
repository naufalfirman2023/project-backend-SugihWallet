<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('/create-otp', [AuthController::class, 'create_otp']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('/profil', [HomeController::class, 'profil']);

// transaksi
Route::post('transfer', [TransaksiController::class, 'transfer']);
Route::get('history', [TransaksiController::class, 'history']);
