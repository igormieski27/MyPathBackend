<?php

namespace App\Http\Controllers;

use App\Services\TarefaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class TarefaController extends BaseController
{
    public function listar(Request $request, TarefaService $TarefasService)
    {
        return $TarefasService->listarTarefas();
    }

    public function buscarTarefa(Request $request, string $id, TarefaService $TarefasService)
    {
        return $TarefasService->findOne($id);
    }

    public function save(Request $request, TarefaService $TarefasService)
    {
        $request->validate([
            'id' => 'numeric',
            'id_usuario' => 'required|numeric',
            'title' => 'string',
            'description' => 'string',
            'reward_exp' => 'numeric',
            'reward_gold' => 'numeric',
            'id_category' => 'numeric',
            'deadline' => 'date'

        ]);

        return $TarefasService->save($request->only([
            'id',
            'id_usuario',
            'title',
            'description',
            'reward_exp',
            'reward_gold',
            'id_category',
            'deadline'
        ]));
    }

    public function delete(Request $request, TarefaService $TarefasService)
    {
        return $TarefasService->delete($request->only(['id']));
    }
}
