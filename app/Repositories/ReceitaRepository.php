<?php

namespace App\Repositories;

use App\Models\Receita;

class ReceitaRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(Receita::class);
    }

    public function findOneById(string $id)
    {
        return $this->model
            ->select('*')
            ->where('id', $id)
            ->first();
    }

    public function findOneByIdShow(string $id)
    {
        return $this->model
            ->select(
                'receitas.id',
                'clientes.id as id_cliente',
                'data_vencimento',
                'clientes.nome as cliente_nome'
            )
            ->join('clientes', 'id_cliente', '=', 'clientes.id')
            ->where('receitas.id', $id)
            ->first();
    }

    public function findAll()
    {
        return $this->model
            ->selectRaw(
                'receitas.id,'.
                'clientes.id as id_cliente,'.
                'clientes.nome as cliente_nome,'.
                'clientes.cnpj_cpf as cliente_cnpj_cpf,'.
                'data_vencimento,'.
                '(select group_concat(id_produto) FROM receitas_produtos WHERE receitas.id = receitas_produtos.id_receita) as id_produtos,'.
                '(select group_concat(descricao) FROM receitas_produtos inner join produtos ON receitas_produtos.id_produto = produtos.id WHERE receitas.id = receitas_produtos.id_receita) as remedios_nomes'
            )
            ->join('clientes', 'id_cliente', '=', 'clientes.id')
            ->get();
    }

    public function findOneByIdClienteAndIdProduto($id_cliente, $id_produto)
    {
        return $this->model
            ->select('*')
            ->join('receitas_produtos', 'receitas.id', '=', 'receitas_produtos.id_receita')
            ->where('receitas.id_cliente', $id_cliente)
            ->where('receitas_produtos.id_produto', $id_produto)
            ->where('receitas.data_vencimento', '>', now())
            ->orderBy('receitas.id', 'desc')
            ->first();
    }
}