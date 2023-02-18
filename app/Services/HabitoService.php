<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Repositories\HabitoRepository;
use Illuminate\Support\Facades\DB;

class HabitoService extends BaseService
{
    protected HabitoRepository $repository;

    public function __construct(
        Request $request,
        HabitoRepository $repository,
    )
    {
        parent::__construct($request);

        $this->repository = $repository;
    }

    public function listarHabitos()
    {
        $Habitos = $this->repository->listar();

        return $this->responseSuccess($Habitos);
    }

    public function findOne(string $id)
    {
        $Habito = $this->repository->findOneById($id);

        return $this->responseSuccess($Habito);
    }

    public function save(array $body)
    {
        try {
            DB::beginTransaction();

            $Habito = isset($body['id']) 
                ? $this->repository->findOneById($body['id']) 
                : $this->repository->create($body);
            
            if (isset($body['id'])) {
                if (!$Habito) {
                    return $this->responseNotFound(trans('messages.Habito.nao_localizado'));
                }

                $Habito->fill($body);
            }

            $this->repository->save($Habito);

            DB::commit();

            return $this->response(
                trans(isset($body['id']) ? 'messages.Habito.alterado' : 'messages.Habito.cadastrado'),
                !isset($body['id']) ? 200 : 201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e);
        }
    }

    public function delete(array $body)
    {
        $Habito = $this->repository->findOneById($body['id']);
        
        if (!$Habito) {
            return $this->responseNotFound(trans('messages.Habito.nao_localizado'));
        }

        try {
            DB::beginTransaction();

            $Habito->delete();

            DB::commit();

            return $this->responseSuccess(trans('messages.Habito.excluido'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFailure(trans('messages.Habito.nao_localizado'));
        }
    }
}