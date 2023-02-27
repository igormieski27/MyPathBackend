<?php

namespace App\Http\Controllers;

use App\Services\HabitoService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class HabitoController extends BaseController
{
    public function listar(Request $request, HabitoService $HabitosService)
    {
        return $HabitosService->listarHabitos();
    }

    public function buscarHabito(Request $request, string $id, HabitoService $HabitosService)
    {
        return $HabitosService->findOne($id);
    }

    public function save(Request $request, HabitoService $HabitosService)
    {
        $request->validate([
            'id' => 'numeric',
            'id_usuario' => 'numeric',
            'title' => 'string',
            'icon' => 'string',
            'color' => 'string',
            'period' => 'numeric',
            'days' => 'string',
            'value' => "boolean"
        ]);

        return $HabitosService->save($request->only([
            'id',
            'id_usuario',
            'title',
            'icon',
            'color',
            'period',
            'days',
            'value'
        ]));
    }

    public function delete(Request $request, string $id, HabitoService $HabitosService)
    {
        return $HabitosService->delete($id);
    }
}
