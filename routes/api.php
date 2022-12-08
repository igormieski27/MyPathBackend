<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [UsuarioController::class, 'login']);
});

Route::group(['prefix' => 'cliente'], function() {
    Route::get('/', [ClienteController::class, 'index']);
    Route::post('/', [ClienteController::class, 'save']);
    Route::delete('/', [ClienteController::class, 'delete']);
});
