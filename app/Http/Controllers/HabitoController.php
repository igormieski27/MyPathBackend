<?php

namespace App\Http\Controllers;

use App\Services\HabitoService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class HabitoController extends BaseController
{
    public function index(Request $request, HabitoService $HabitosService)
    {
        return $HabitosService->carregarHabitos();
    }

    public function buscarHabito(Request $request, string $id, HabitoService $HabitosService)
    {
        return $HabitosService->findOne($id);
    }

    public function carregarCombo(Request $request, HabitoService $HabitoService)
    {
        return $HabitoService->carregarCombo();
    }
    
    public function save(Request $request, HabitoService $HabitosService)
    {
        return $HabitosService->save($request->only([
            'id',
            'id_usuario',
            'titulo',
            'icon',
            'period',
            'color',
        ]));
    }

    public function delete(Request $request, HabitoService $HabitosService)
    {
        return $HabitosService->delete($request->only(['id']));
    }
}
