<?php

namespace App\Services;

use App\Repositories\ProdutoRepository;
use App\Repositories\SaidaProdutoRepository;
use Illuminate\Http\Request;

use App\Repositories\SaidaRepository;
use Illuminate\Support\Facades\DB;

class SaidaService extends BaseService
{
    protected SaidaRepository $repository;
    protected SaidaProdutoRepository $saidaProdutoRepository;
    protected ProdutoRepository $produtoRepository;

    public function __construct(
        Request $request,
        SaidaRepository $repository,
        SaidaProdutoRepository $saidaProdutoRepository,
        ProdutoRepository $produtoRepository,
    )
    {
        parent::__construct($request);

        $this->repository = $repository;
        $this->saidaProdutoRepository = $saidaProdutoRepository;
        $this->produtoRepository = $produtoRepository;
    }

    public function carregarSaidas()
    {
        $saidas = $this->repository->findAll();

        return $this->responseSuccess($saidas);
    }

    public function findOne(string $id)
    {
        $saida = $this->repository->findOneByIdShow($id);
        $produtos = $this->saidaProdutoRepository->findByIdSaida($id);

        return $this->responseSuccess(['saida' => $saida, 'produtos' => $produtos ]);
    }

    public function save(array $body, array $produtos)
    {
        try {
            DB::beginTransaction();

            $saida = isset($body['id']) 
                ? $this->repository->findOneById($body['id']) 
                : $this->repository->create($body);
            
            if (isset($body['id'])) {
                if (!$saida) {
                    return $this->responseNotFound(trans('messages.saida.nao_localizada'));
                }

                $saida->fill($body);
            }

            $this->repository->save($saida);

            $produtos = $produtos['produtos'];

            foreach ($produtos as $item) {
                $produto = $this->produtoRepository->findOneById($item['id']);

                $this->saidaProdutoRepository
                    ->createQueryBuilder()
                    ->create([
                        'id_saida' => $saida->id,
                        'id_produto' => $produto->id,
                        'unidade_medida' => $produto->unidade_medida,
                        'valor_unitario' => $item['valor_unitario'],
                        'quantidade' => $item['quantidade'],
                        'valor_total' => $item['valor_total'],
                        'desconto' => $item['desconto'],
                    ]);
            }

            DB::commit();

            return $this->response(
                trans(isset($body['id']) ? 'messages.saida.alterada' : 'messages.saida.cadastrada'),
                !isset($body['id']) ? 200 : 201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e);
        }
    }

    public function delete(array $body)
    {
        $saida = $this->repository->findOneById($body['id']);
        
        if (!$saida) {
            return $this->responseNotFound(trans('messages.saida.nao_localizada'));
        }

        try {
            DB::beginTransaction();

            $saida->delete();

            DB::commit();

            return $this->responseSuccess(trans('messages.saida.excluida'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFailure(trans('messages.saida.nao_localizada'));
        }
    }
}