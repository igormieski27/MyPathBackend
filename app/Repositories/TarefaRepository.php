<?php

namespace App\Repositories;

use App\Models\Tarefa;
use Illuminate\Database\Eloquent\Collection;

class TarefaRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(Tarefa::class);
    }

    public function listar(string $idUsuario): Collection
    {
        return $this->model
            ->select('tarefas.*')
            ->from('tarefas')
            ->leftJoin('usuario_tarefa', function($join) use ($idUsuario) {
                $join->on('tarefas.id', '=', 'usuario_tarefa.id_tarefa')
                     ->where('usuario_tarefa.id_usuario', '=', $idUsuario);
            })
            ->whereNull('usuario_tarefa.id_tarefa')
            ->get();
    }
    

    public function findOneById(string $id)
    {
        return $this->model
            ->select('*')
            ->where('id', $id)
            ->first();
    }
    
}