<?php

namespace App\Repositories;

use App\Models\Saida;

class SaidaRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(Saida::class);
    }

    public function findAll()
    {
        return $this->model
            ->selectRaw(
                'saidas.id,'.
                'id_cliente,'.
                'clientes.nome as cliente_nome,'.
                'clientes.cnpj_cpf as cliente_cnpj_cpf,'.
                'id_funcionario,'.
                'usuarios.nome as funcionario_nome,'.
                'data_emissao,'.
                'valor_total,'.
                'tipo_pagamento,'.
                'observacao,'.
                '(select group_concat(descricao) from saidas_produtos inner join produtos ON saidas_produtos.id_produto = produtos.id where saidas.id = saidas_produtos.id_saida) as produtos'
            )
            ->join('clientes', 'clientes.id', '=', 'id_cliente')
            ->join('usuarios', 'usuarios.id', '=', 'id_funcionario')
            ->get();
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
            ->selectRaw(
                'saidas.id,'.
                'id_cliente,'.
                'clientes.nome as cliente_nome,'.
                'id_funcionario,'.
                'usuarios.nome as funcionario_nome,'.
                'data_emissao,'.
                'valor_total,'.
                'tipo_pagamento,'.
                'observacao'
            )
            ->join('clientes', 'clientes.id', '=', 'id_cliente')
            ->join('usuarios', 'usuarios.id', '=', 'id_funcionario')
            ->where('saidas.id', $id)
            ->get();
    }
}