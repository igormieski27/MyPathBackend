<?php

namespace App\Http\Controllers;

use App\Services\SaidaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class SaidaController extends BaseController
{
    public function index(Request $request, SaidaService $saidaService)
    {
        return $saidaService->carregarSaidas();
    }

    public function buscarSaida(Request $request, string $id, SaidaService $saidaService)
    {
        return $saidaService->findOne($id);
    }

    public function save(Request $request, SaidaService $saidaService)
    {
        return $saidaService->save($request->only([
            'id',
            'id_cliente',
            'id_funcionario',
            'observacao',
            'data_emissao',
            'tipo_pagamento',
            'valor_total',
        ]), $request->only(['produtos']));
    }

    public function delete(Request $request, SaidaService $saidaService)
    {
        return $saidaService->delete($request->only(['id']));
    }
}
