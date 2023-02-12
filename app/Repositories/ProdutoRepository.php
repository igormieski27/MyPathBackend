<?php

namespace App\Repositories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Collection;

class ProdutoRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(Produto::class);
    }

    public function findAll(): Collection
    {
        return $this->model
            ->select('*')
            ->get();
    }

    public function carregarProdutos(): Collection
    {
        return $this->model
            ->select('id', 'descricao')
            ->get();
    }

    public function comboRemedios(): Collection
    {
        return $this->model
            ->select('id', 'descricao')
            ->where('controlado', 'S')
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