<?php

namespace App\Services;

use App\Repositories\EntradaProdutoRepository;
use Illuminate\Http\Request;

use App\Repositories\EntradaRepository;
use App\Repositories\ProdutoRepository;
use Illuminate\Support\Facades\DB;

class EntradaService extends BaseService
{
    protected EntradaRepository $repository;
    protected EntradaProdutoRepository $entradaProdutoRepository;
    protected ProdutoRepository $produtoRepository;

    public function __construct(
        Request $request,
        EntradaRepository $repository,
        EntradaProdutoRepository $entradaProdutoRepository,
        ProdutoRepository $produtoRepository,
    )
    {
        parent::__construct($request);

        $this->repository = $repository;
        $this->entradaProdutoRepository = $entradaProdutoRepository;
        $this->produtoRepository = $produtoRepository;
    }

    public function carregarEntradas()
    {
        $entradas = $this->repository->findAll();

        return $this->responseSuccess($entradas);
    }

    public function findOne(string $id)
    {
        $entrada = $this->repository->findOneByIdShow($id);
        $produtos = $this->entradaProdutoRepository->findByIdEntrada($id);

        return $this->responseSuccess(['entrada' => $entrada, 'produtos' => $produtos ]);
    }

    public function save(array $body, array $produtos)
    {
        try {
            DB::beginTransaction();

            $entrada = isset($body['id']) 
                ? $this->repository->findOneById($body['id']) 
                : $this->repository->create($body);
            
            if (isset($body['id'])) {
                if (!$entrada) {
                    return $this->responseNotFound(trans('messages.entrada.nao_localizada'));
                }

                $entrada->fill($body);
            }

            $this->repository->save($entrada);

            $produtos = $produtos['produtos'];

            foreach ($produtos as $item) {
                $produto = $this->produtoRepository->findOneById($item['id']);

                $this->entradaProdutoRepository
                    ->createQueryBuilder()
                    ->create([
                        'id_entrada' => $entrada->id,
                        'id_produto' => $produto->id,
                        'valor_unitario' => $item['valor_unitario'],
                        'quantidade' => $item['quantidade'],
                        'valor_total' => $item['valor_total'],
                    ]);
            }

            DB::commit();

            return $this->response(
                trans(isset($body['id']) ? 'messages.entrada.alterada' : 'messages.entrada.cadastrada'),
                !isset($body['id']) ? 200 : 201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e);
        }
    }

    public function delete(array $body)
    {
        $entrada = $this->repository->findOneById($body['id']);
        
        if (!$entrada) {
            return $this->responseNotFound(trans('messages.entrada.nao_localizada'));
        }

        try {
            DB::beginTransaction();

            $entrada->delete();

            DB::commit();

            return $this->responseSuccess(trans('messages.entrada.excluida'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFailure(trans('messages.entrada.nao_localizada'));
        }
    }
}