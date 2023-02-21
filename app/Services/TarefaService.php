<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Repositories\TarefaRepository;
use Illuminate\Support\Facades\DB;

class TarefaService extends BaseService
{
    protected TarefaRepository $repository;

    public function __construct(
        Request $request,
        TarefaRepository $repository,
    )
    {
        parent::__construct($request);

        $this->repository = $repository;
    }

    public function listarTarefas()
    {
        $Tarefas = $this->repository->listar();

        return $this->responseSuccess($Tarefas);
    }

    public function findOne(string $id)
    {
        $Tarefa = $this->repository->findOneById($id);

        return $this->responseSuccess($Tarefa);
    }

    public function save(array $body)
    {
        try {
            DB::beginTransaction();

            $Tarefa = isset($body['id']) 
                ? $this->repository->findOneById($body['id']) 
                : $this->repository->create($body);
            
            if (isset($body['id'])) {
                if (!$Tarefa) {
                    return $this->responseNotFound(trans('messages.Tarefa.nao_localizado'));
                }

                $Tarefa->fill($body);
            }

            $this->repository->save($Tarefa);

            DB::commit();

            return $this->response(
                trans(isset($body['id']) ? 'messages.Tarefa.alterado' : 'messages.Tarefa.cadastrado'),
                !isset($body['id']) ? 200 : 201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e);
        }
    }

    public function delete(array $body)
    {
        $Tarefa = $this->repository->findOneById($body['id']);
        
        if (!$Tarefa) {
            return $this->responseNotFound(trans('messages.Tarefa.nao_localizado'));
        }

        try {
            DB::beginTransaction();

            $Tarefa->delete();

            DB::commit();

            return $this->responseSuccess(trans('messages.Tarefa.excluido'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFailure(trans('messages.Tarefa.nao_localizado'));
        }
    }
}