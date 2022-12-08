<?php

namespace App\Http\Controllers;

use App\Services\ClienteService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ClienteController extends BaseController
{
    public function index(Request $request, ClienteService $clienteService) {
        return $clienteService->carregarClientes();
    }

    public function save(Request $request, ClienteService $clienteService) {
        return $clienteService->save($request->only([
            'id',
            'nome',
            'cnpj_cpf',
            'endereco_rua',
            'endereco_complemento',
            'endereco_bairro',
            'endereco_numero',
            'telefone',
            'email',
        ]));
    }

    public function delete(Request $request, ClienteService $clienteService) {
        return $clienteService->delete($request->only('id'));    
    }
}
