<?php

namespace App\Repositories;

use App\Models\Entrada;

class EntradaRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(Entrada::class);
    }

    public function findAll()
    {
        return $this->model
            ->selectRaw(
                'entradas.id,'.
                'id_funcionario,'.
                'usuarios.nome as funcionario_nome,'.
                'data_emissao,'.
                'valor_total,'.
                'observacao,'.
                '(select group_concat(descricao) from entradas_produtos inner join produtos ON entradas_produtos.id_produto = produtos.id where entradas.id = entradas_produtos.id_entrada) as produtos'
            )
            ->join('usuarios', 'usuarios.id', '=', 'id_funcionario')
            ->get();
    }

    public function findOneByIdShow(string $id)
    {
        return $this->model
            ->selectRaw(
                'entradas.id,'.
                'id_funcionario,'.
                'usuarios.nome as funcionario_nome,'.
                'data_emissao,'.
                'valor_total,'.
                'observacao'
            )
            ->join('usuarios', 'usuarios.id', '=', 'id_funcionario')
            ->where('entradas.id', $id)
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