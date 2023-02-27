<?php

namespace App\Repositories;

use App\Models\UsuarioTarefa;
use Illuminate\Database\Eloquent\Collection;

class UsuarioTarefaRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(UsuarioTarefa::class);
    }

    public function findByIdUsuario(string $id)
    {
        return $this->model
            ->select('usuario_tarefa.*', 'tarefas.*')
            ->join('tarefas', 'tarefas.id', '=', 'usuarioTarefas.id_tarefa')
            ->where('id_usuario', $id)
            ->get();
    }
}