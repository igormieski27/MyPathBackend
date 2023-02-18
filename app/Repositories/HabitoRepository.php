<?php

namespace App\Repositories;

use App\Models\Habito;
use Illuminate\Database\Eloquent\Collection;

class HabitoRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(Habito::class);
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