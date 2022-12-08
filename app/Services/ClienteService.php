<?php

namespace App\Services;

use App\Repositories\ClienteRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ClienteService extends BaseService
{
    private $clienteRepository;

    public function __construct(
        Request $request,
        ClienteRepository $clienteRepository
    )
    {
        parent::__construct($request);

        $this->clienteRepository = $clienteRepository;    
    }

    public function carregarClientes() {
        $cliente = $this->clienteRepository->findAll();

        return $this->responseSuccess($cliente);
    }

    public function save(array $body) {
        $cliente = isset($body['id']) 
            ? $this->clienteRepository->findOneById($body['id']) 
            : $this->clienteRepository->create($body);
        
        if (isset($body['id'])) {
            if (!$cliente) {
                return $this->responseNotFound(trans('messages.cliente.nao_localizado'));
            }
        }

        $this->clienteRepository->updateOrCreate(['id' => $cliente->id], $body);

        return $this->response(
            trans(isset($body['id']) ? 'messages.cliente.alterado' : 'messages.cliente.cadastrado'),
            !isset($body['id']) ? 200 : 201
        );
    }

    public function delete(array $body) {
        $cliente = $this->clienteRepository->findOneById($body['id']);
        
        if (!$cliente) {
            return $this->responseNotFound(trans('messages.cliente.nao_localizado'));    
        }
    }
}
