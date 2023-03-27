<?php

namespace App\Http\Controllers;

use App\Services\UsuarioService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class UsuarioController extends BaseController
{
    public function login(Request $request, UsuarioService $usuarioService)
    {
        $request->validate([
            'email' => 'required|string|email',
            'senha' => 'required|string'
        ]);

        return $usuarioService->logar($request->only(['email', 'senha']));
    }

    public function cadastrar(Request $request, UsuarioService $usuarioService)
    {
        $request->validate([
            'nome' => 'required|string',
            'email' => 'required|string|email',
            'senha' => 'required|string'
        ]);

        return $usuarioService->save($request->only(['nome', 'email', 'senha']));
    }

    public function carregar(Request $request, string $id, UsuarioService $usuarioService)
    {
        return $usuarioService->findOne($id);
    }

    public function logout(Request $request, UsuarioService $usuarioService)
    {
        return $usuarioService->deslogar($request);
    }

    public function save(Request $request, UsuarioService $usuarioService)
    {
        $request->validate([
            'nome' => 'string',
            'email' => 'string|email',
            'personagem' => 'string',
            'level' => 'numeric',
            'xp' => 'numeric'
        ]);

        return $usuarioService->save($request->only([
            'id',
            'nome',
            'email',
            'personagem',
            'level',
            'xp'
        ]));
    }
    
    public function buscarAtividadeSemanal(Request $request, string $id, UsuarioService $usuarioService){
        return $usuarioService->buscarAtividadeSemanal($id);
    }

    public function comprarItem(Request $request, UsuarioService $usuarioService){
        return $usuarioService->comprarItem($request->only(['id_usuario', 'id_item']));
    }

}
