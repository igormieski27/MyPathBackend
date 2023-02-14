<?php

namespace App\Repositories;

use App\Models\ReceitaProduto;
use Illuminate\Database\Eloquent\Collection;

class ReceitaProdutoRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(ReceitaProduto::class);
    }

    public function findAll()
    {
        return $this->model
            ->select('id_receita', 'id_produto', 'descricao')
            ->join('produtos', 'produtos.id', '=', 'receitas_produtos.id_produto')
            ->get();
    }

    public function findByIdReceita($id)
    {
        return $this->model
            ->selectRaw(
                'receitas_produtos.*,'.
                'produtos.descricao,'.
                'produtos.unidade_medida,'.
                'produtos.valor as valor_unitario,'.
                '(produtos.valor * receitas_produtos.quantidade) as valor_total'
            )
            ->join('produtos', 'produtos.id', '=', 'receitas_produtos.id_produto')
            ->where('id_receita', $id)
            ->get();
    }
}