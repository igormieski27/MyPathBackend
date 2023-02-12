<?php

namespace App\Http\Controllers;

use App\Services\ProdutoService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ProdutoController extends BaseController
{
    public function index(Request $request, ProdutoService $produtosService)
    {
        return $produtosService->carregarProdutos();
    }

    public function buscarProduto(Request $request, string $id, ProdutoService $produtosService)
    {
        return $produtosService->findOne($id);
    }

    public function carregarCombo(Request $request, ProdutoService $produtoService)
    {
        return $produtoService->carregarCombo();
    }

    public function carregarComboRemedio(Request $request, ProdutoService $produtoService)
    {
        return $produtoService->carregarComboRemedio();
    }

    public function save(Request $request, ProdutoService $produtosService)
    {
        return $produtosService->save($request->only([
            'id',
            'descricao',
            'valor',
            'unidade_medida',
            'codigo_barras',
            'lote',
            'remedio',
            'controlado',
        ]));
    }

    public function delete(Request $request, ProdutoService $produtosService)
    {
        return $produtosService->delete($request->only(['id']));
    }
}
