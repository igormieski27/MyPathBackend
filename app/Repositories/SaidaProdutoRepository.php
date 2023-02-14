<?php

namespace App\Repositories;

use App\Models\SaidaProduto;
use Illuminate\Database\Eloquent\Collection;

class SaidaProdutoRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(SaidaProduto::class);
    }

    public function findByIdSaida(string $id)
    {
        return $this->model
            ->select('saidas_produtos.*', 'produtos.descricao')
            ->join('produtos', 'produtos.id', '=', 'saidas_produtos.id_produto')
            ->where('id_saida', $id)
            ->get();
    }
}