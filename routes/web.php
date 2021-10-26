<?php

use App\Http\Controllers\ReportController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/pdf', [ReportController::class, 'generar']);

Route::get('/factura1', [ReportController::class, 'factura1']);
Route::get('/factura2', [ReportController::class, 'factura2']);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
