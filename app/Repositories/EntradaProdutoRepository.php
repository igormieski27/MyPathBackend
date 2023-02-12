<?php

namespace App\Repositories;

use App\Models\EntradaProduto;
use Illuminate\Database\Eloquent\Collection;

class EntradaProdutoRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(EntradaProduto::class);
    }

    public function findByIdEntrada(string $id): Collection
    {
        return $this->model
            ->select('entradas_produtos.*', 'produtos.descricao')
            ->join('produtos', 'produtos.id', '=', 'entradas_produtos.id_produto')
            ->where('id_entrada', $id)
            ->get();
    }
}