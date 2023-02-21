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

    public function listar(): Collection
    {
        return $this->model
            ->select('*')
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