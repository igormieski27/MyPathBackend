<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EntradaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProdutoController;
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
    Route::post('/logout', [UsuarioController::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function () {
        Route::group(['prefix' => 'funcionario'], function () {
            Route::get('/combo', [UsuarioController::class, 'carregarCombo']);
        });
        
        Route::group(['prefix' => 'cliente'], function () {
            Route::get('/', [ClienteController::class, 'index']);
            Route::get('/combo', [ClienteController::class, 'carregarCombo']);
            Route::post('/', [ClienteController::class, 'save']);
            Route::delete('/excluir', [ClienteController::class, 'delete']);
        });
        
        Route::group(['prefix' => 'produto'], function () {
            Route::get('/', [ProdutoController::class, 'index']);
            Route::get('/buscar/{id}', [ProdutoController::class, 'buscarProduto']);
            Route::get('/combo', [ProdutoController::class, 'carregarCombo']);
            Route::get('/combo-remedio', [ProdutoController::class, 'carregarComboRemedio']);
            Route::post('/', [ProdutoController::class, 'save']);
            Route::delete('/excluir', [ProdutoController::class, 'delete']);
        });
        
        Route::group(['prefix' => 'receita'], function () {
            Route::get('/', [ReceitaController::class, 'index']);
            Route::get('/buscar/{id}', [ReceitaController::class, 'buscarReceita']);
            Route::post('/', [ReceitaController::class, 'save']);
            Route::delete('/excluir', [ReceitaController::class, 'delete']);
            Route::post('/verificar-receita', [ReceitaController::class, 'verificarRemedio']);
        });
        
        Route::group(['prefix' => 'saida'], function () {
            Route::get('/', [SaidaController::class, 'index']);
            Route::get('/buscar/{id}', [SaidaController::class, 'buscarSaida']);
            Route::post('/', [SaidaController::class, 'save']);
            Route::delete('/excluir', [SaidaController::class, 'delete']);
        });
        
        Route::group(['prefix' => 'entrada'], function () {
            Route::get('/', [EntradaController::class, 'index']);
            Route::get('/buscar/{id}', [EntradaController::class, 'buscarEntrada']);
            Route::post('/', [EntradaController::class, 'save']);
            Route::delete('/excluir', [EntradaController::class, 'delete']);
        });
    });
});


