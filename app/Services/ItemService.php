<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Repositories\ItemRepository;
use App\Repositories\UsuarioItemRepository;
use App\Repositories\UsuarioRepository;
use Illuminate\Support\Facades\DB;

class ItemService extends BaseService
{
    protected ItemRepository $repository;
    protected UsuarioItemRepository $usuarioItemRepository;
    protected UsuarioRepository $usuarioRepository;
    public function __construct(
        Request $request,
        ItemRepository $repository,
        UsuarioItemRepository $usuarioItemRepository,
        UsuarioRepository $usuarioRepository,
    )
    {
        parent::__construct($request);

        $this->repository = $repository;
        $this->usuarioItemRepository = $usuarioItemRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    public function listarItem(string $idUsuario)
    {
        $Items = $this->repository->listar($idUsuario);

        return $this->responseSuccess($Items);
    }

    public function findOne(string $id)
    {
        $Item = $this->repository->findOneById($id);

        return $this->responseSuccess($Item);
    }

    public function save(array $body)
    {
        try {
            DB::beginTransaction();

            $Item = isset($body['id']) 
                ? $this->repository->findOneById($body['id']) 
                : $this->repository->create($body);
            
            if (isset($body['id'])) {
                if (!$Item) {
                    return $this->responseNotFound(trans('messages.Item.nao_localizado'));
                }

                $Item->fill($body);
            }

            $this->repository->save($Item);

            DB::commit();

            return $this->response(
                isset($body['id']) ? trans('messages.Item.alterado') : trans('messages.Item.cadastrado'),
                !isset($body['id']) ? 200 : 201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e);
        }
    }

    public function delete(string $id)
    {
        $Item = $this->repository->findOneById($id);
        
        if (!$Item) {
            return $this->responseNotFound(trans('messages.Item.nao_localizado'));
        }

        try {
            DB::beginTransaction();

            $Item->delete();

            DB::commit();

            return $this->responseSuccess(trans('messages.Item.excluido'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFailure(trans('messages.Item.nao_localizado'));
        }
    }
}