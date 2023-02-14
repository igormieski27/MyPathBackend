<?php

namespace App\Http\Controllers;

use App\Services\ReceitaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ReceitaController extends BaseController
{
    public function index(Request $request, ReceitaService $receitaService)
    {
        return $receitaService->carregarReceitas();
    }

    public function buscarReceita(Request $request, string $id, ReceitaService $receitaService)
    {
        return $receitaService->findOne($id);
    }

    public function save(Request $request, ReceitaService $receitaService)
    {
        return $receitaService->save($request->only([
            'id',
            'id_cliente',
            'data_vencimento',
        ]), $request->only(['produtos']));
    }

    public function delete(Request $request, ReceitaService $receitaService)
    {
        return $receitaService->delete($request->only(['id']));
    }
    
    public function verificarRemedio(Request $request, ReceitaService $receitaService)
    {
        return $receitaService->verificarRemedio($request->only(['id_cliente', 'id_produto']));
    }
}
