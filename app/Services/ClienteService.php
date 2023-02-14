<?php

namespace App\Services;

use App\Repositories\ClienteRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteService extends BaseService
{
    protected ClienteRepository $repository;

    public function __construct(
        Request $request,
        ClienteRepository $repository
    )
    {
        parent::__construct($request);

        $this->repository = $repository;
    }

    public function carregarClientes()
    {
        $cliente = $this->repository->findAll();

        return $this->responseSuccess($cliente);
    }

    public function carregarComboBox()
    {
        $clientes = $this->repository->combo();

        return $this->responseSuccess($clientes);
    }

    public function save(array $body)
    {
        try {
            DB::beginTransaction();

            $cliente = isset($body['id']) 
                ? $this->repository->findOneById($body['id']) 
                : $this->repository->create($body);
            
            if (isset($body['id'])) {
                if (!$cliente) {
                    return $this->responseNotFound(trans('messages.cliente.nao_localizado'));
                }

                $cliente->fill($body);
            }

            $this->repository->save($cliente);

            DB::commit();

            return $this->response(
                trans(isset($body['id']) ? 'messages.cliente.alterado' : 'messages.cliente.cadastrado'),
                !isset($body['id']) ? 200 : 201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e);
        }
    }

    public function delete(array $body)
    {
        $cliente = $this->repository->findOneById($body['id']);
        
        if (!$cliente) {
            return $this->responseNotFound(trans('messages.cliente.nao_localizado'));
        }

        try {
            DB::beginTransaction();

            $cliente->delete();

            DB::commit();

            return $this->responseSuccess(trans('messages.cliente.excluido'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFailure(trans('messages.cliente.nao_localizado'));
        }
    }
}
