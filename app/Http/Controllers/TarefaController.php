<?php

namespace App\Http\Controllers;

use App\Services\TarefaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class TarefaController extends BaseController
{
    public function listar(Request $request, string $idUsuario, TarefaService $TarefasService)
    {
        return $TarefasService->listarTarefas($idUsuario);
    }

    public function buscarTarefa(Request $request, string $id, TarefaService $TarefasService)
    {
        return $TarefasService->findOne($id);
    }

    public function save(Request $request, TarefaService $TarefasService)
    {
        $request->validate([
            'id' => 'numeric',
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

    public function carregarTarefas(Request $request, string $id, TarefaService $TarefasService){
        return $TarefasService->findByIdUsuario($id);
    }

    public function concluirTarefa(Request $request, TarefaService $TarefasService){
        $request->validate([
            'id_usuario' => 'required|string',
            'id_tarefa' => 'required|string',
            'status' => 'boolean'
        ]);
        return $TarefasService->concluirTarefa($request->only(['id_usuario', 'id_tarefa', 'status']));

    }
    
    public function vincularTarefa(Request $request, TarefaService $TarefasService){
        $request->validate([
            'id_usuario' => 'required|string',
            'id_tarefa' => 'required|string',
            'status' => 'boolean'
        ]);
        return $TarefasService->vincularTarefa($request->only(['id_usuario', 'id_tarefa', 'status']));
    }




}
