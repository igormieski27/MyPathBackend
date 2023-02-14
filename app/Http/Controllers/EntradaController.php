<?php

namespace App\Http\Controllers;

use App\Services\EntradaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class EntradaController extends BaseController
{
    public function index(Request $request, EntradaService $entradaService)
    {
        return $entradaService->carregarEntradas();
    }

    public function buscarEntrada(Request $request, string $id, EntradaService $entradaService)
    {
        return $entradaService->findOne($id);
    }

    public function save(Request $request, EntradaService $entradaService)
    {
        return $entradaService->save($request->only([
            'id',
            'id_cliente',
            'id_funcionario',
            'observacao',
            'data_emissao',
            'tipo_pagamento',
            'valor_total',
        ]), $request->only(['produtos']));
    }

    public function delete(Request $request, EntradaService $entradaService)
    {
        return $entradaService->delete($request->only(['id']));
    }
}
