<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Repositories\ProdutoRepository;
use Illuminate\Support\Facades\DB;

class ProdutoService extends BaseService
{
    protected ProdutoRepository $repository;

    public function __construct(
        Request $request,
        ProdutoRepository $repository,
    )
    {
        parent::__construct($request);

        $this->repository = $repository;
    }

    public function carregarProdutos()
    {
        $produtos = $this->repository->findAll();

        return $this->responseSuccess($produtos);
    }

    public function findOne(string $id)
    {
        $produto = $this->repository->findOneById($id);

        return $this->responseSuccess($produto);
    }

    public function carregarCombo()
    {
        $produtos = $this->repository->carregarProdutos();

        return $this->responseSuccess($produtos);
    }

    public function carregarComboRemedio()
    {
        $remedios = $this->repository->comboRemedios();

        return $this->responseSuccess($remedios);
    }

    public function save(array $body)
    {
        try {
            DB::beginTransaction();

            $produto = isset($body['id']) 
                ? $this->repository->findOneById($body['id']) 
                : $this->repository->create($body);
            
            if (isset($body['id'])) {
                if (!$produto) {
                    return $this->responseNotFound(trans('messages.produto.nao_localizado'));
                }

                $produto->fill($body);
            }

            $this->repository->save($produto);

            DB::commit();

            return $this->response(
                trans(isset($body['id']) ? 'messages.produto.alterado' : 'messages.produto.cadastrado'),
                !isset($body['id']) ? 200 : 201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e);
        }
    }

    public function delete(array $body)
    {
        $produto = $this->repository->findOneById($body['id']);
        
        if (!$produto) {
            return $this->responseNotFound(trans('messages.produto.nao_localizado'));
        }

        try {
            DB::beginTransaction();

            $produto->delete();

            DB::commit();

            return $this->responseSuccess(trans('messages.produto.excluido'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFailure(trans('messages.produto.nao_localizado'));
        }
    }
}