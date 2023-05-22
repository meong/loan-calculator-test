<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('/', [ Controller::class, 'index' ] )->name("index");
Route::get('/create', [ Controller::class, 'create' ] )->name("create");
Route::post('/submit', [ Controller::class, 'submit' ] )->name("submit");

Route::get('/preview-schedules', [ Controller::class, 'getPreviewSchedules' ] )->name("get-preview-schedules");

Route::get('/schedules/{loan}', [ Controller::class, 'schedules' ] )->name("schedules");

Route::get('/repayment/{loan}', [ Controller::class, 'repayment' ] )->name("repayment");
Route::post('/repayment/{loan}/submit', [ Controller::class, 'repaymentSubmit' ] )->name("repayment-submit");
