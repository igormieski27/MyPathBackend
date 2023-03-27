<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EntradaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HabitoController;
use App\Http\Controllers\ItemController;
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
    // HABITOS
    Route::group(['prefix' => 'habito'], function () {
        Route::get('/listar/{id}', [HabitoController::class, 'listar']);
        Route::get('/carregar/{id}', [HabitoController::class, 'buscarHabito']);
        Route::post('/salvar', [HabitoController::class, 'save']);
        Route::delete('/excluir/{id}', [HabitoController::class, 'delete']);
    });

    // TAREFAS
    Route::group(['prefix' => 'tarefa'], function () {
        Route::get('/listar/{id}', [TarefaController::class, 'listar']);
        Route::get('/carregar/{id}', [TarefaController::class, 'carregarTarefas']);
        Route::post('/salvar', [TarefaController::class, 'save']);
        Route::delete('/excluir/{id}', [TarefaController::class, 'delete']);
        Route::post('/concluirTarefa', [TarefaController::class, 'concluirTarefa']);
        Route::post('/vincularTarefa', [TarefaController::class, 'vincularTarefa']);
    });

    // ITENS
    Route::group(['prefix' => 'item'], function () {
        Route::get('/listar/{id}', [ItemController::class, 'listar']);
        Route::get('/carregar/{id}', [ItemController::class, 'buscarItem']);
        Route::post('/salvar', [ItemController::class, 'save']);
        Route::delete('/excluir/{id}', [ItemController::class, 'delete']);
    });    

    // USUARIO
    Route::group(['prefix' => 'usuario'], function () {
        Route::get('/carregar/{id}', [UsuarioController::class, 'carregar']);
        Route::get('/buscarAtividadeSemanal/{id}', [UsuarioController::class, 'buscarAtividadeSemanal']);
        Route::post('/salvar', [UsuarioController::class, 'save']);
        Route::post('/comprarItem', [UsuarioController::class, 'comprarItem']);
        Route::get('/listarInventario/{id}', [UsuarioController::class, 'listarInventario']);
    });
});


