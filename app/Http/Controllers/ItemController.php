<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ItemController extends BaseController
{
    public function listar(Request $request, string $id, ItemService $ItemService)
    {
        return $ItemService->listarItem($id);
    }

    public function buscarItem(Request $request, string $id, ItemService $ItemService)
    {
        return $ItemService->findOne($id);
    }

    public function save(Request $request, ItemService $ItemService)
    {
        $request->validate([
            'id' => 'numeric',
            'nome' => 'string',
            'valor' => 'numeric',
            'referencia' => 'string'
        ]);

        return $ItemService->save($request->only([
            'id',
            'nome',
            'valor',
            'referencia',
            'categoria'
        ]));
    }

    public function delete(Request $request, string $id, ItemService $ItemService)
    {
        return $ItemService->delete($id);
    }
}
