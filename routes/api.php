<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EntradaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HabitoController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\SaidaController;

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
    Route::post('/new', [UsuarioController::class, 'cadastrar']);
    Route::post('/logout', [UsuarioController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () { 
    Route::group(['prefix' => 'habito'], function () {
        Route::get('/listar', [HabitoController::class, 'listar']);
        Route::get('/carregar/{id}', [HabitoController::class, 'buscarHabito']);
        Route::post('/salvar', [HabitoController::class, 'save']);
        Route::delete('/excluir', [HabitoController::class, 'delete']);
    });
    Route::group(['prefix' => 'tarefa'], function () {
        Route::get('/listar', [TarefaController::class, 'listar']);
        Route::get('/carregar/{id}', [TarefaController::class, 'buscarTarefa']);
        Route::post('/salvar', [TarefaController::class, 'save']);
        Route::delete('/excluir', [TarefaController::class, 'delete']);
    });
});


