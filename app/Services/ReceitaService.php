<?php

namespace App\Services;

use App\Repositories\ProdutoRepository;
use App\Repositories\ReceitaProdutoRepository;
use Illuminate\Http\Request;

use App\Repositories\ReceitaRepository;
use Illuminate\Support\Facades\DB;

class ReceitaService extends BaseService
{
    protected ReceitaRepository $repository;
    protected ReceitaProdutoRepository $receitaProdutoRepository;
    protected ProdutoRepository $produtoRepository;

    public function __construct(
        Request $request,
        ReceitaRepository $repository,
        ReceitaProdutoRepository $receitaProdutoRepository,
        ProdutoRepository $produtoRepository,
    )
    {
        parent::__construct($request);

        $this->repository = $repository;
        $this->receitaProdutoRepository = $receitaProdutoRepository;
        $this->produtoRepository = $produtoRepository;
    }

    public function carregarReceitas()
    {
        $receitas = $this->repository->findAll();

        return $this->responseSuccess($receitas);
    }

    public function findOne(string $id)
    {
        $receita = $this->repository->findOneByIdShow($id);
        $produtos = $this->receitaProdutoRepository->findByIdReceita($id);

        return $this->responseSuccess(['receita' => $receita, 'produtos' => $produtos ]);
    }

    public function save(array $body, array $produtos)
    {
        try {
            DB::beginTransaction();

            $receita = isset($body['id']) 
                ? $this->repository->findOneById($body['id']) 
                : $this->repository->create($body);
            
            if (isset($body['id'])) {
                if (!$receita) {
                    return $this->responseNotFound(trans('messages.receita.nao_localizada'));
                }

                $receita->fill($body);
            }

            $this->repository->save($receita);

            $produtos = $produtos['produtos'];

            foreach ($produtos as $item) {
                $produto = $this->produtoRepository->findOneById($item['id']);

                $this->receitaProdutoRepository
                    ->createQueryBuilder()
                    ->create([
                        'id_receita' => $receita->id,
                        'id_produto' => $produto->id,
                        'quantidade' => $item['quantidade'],
                    ]);
            }

            DB::commit();

            return $this->response(
                trans(isset($body['id']) ? 'messages.receita.alterada' : 'messages.receita.cadastrada'),
                !isset($body['id']) ? 200 : 201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e);
        }
    }

    public function delete(array $body)
    {
        $receita = $this->repository->findOneById($body['id']);
        
        if (!$receita) {
            return $this->responseNotFound(trans('messages.receita.nao_localizada'));
        }

        try {
            DB::beginTransaction();

            $receita->delete();

            DB::commit();

            return $this->responseSuccess(trans('messages.receita.excluida'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFailure(trans('messages.receita.nao_localizada'));
        }
    }

    public function verificarRemedio(array $body)
    {
        $produto = $this->produtoRepository->findOneById($body['id_produto']);

        if ($produto->controlado == "S") {
            $receita = $this->repository->findOneByIdClienteAndIdProduto($body['id_cliente'], $body['id_produto']);

            if (!$receita) {
                return $this->responseFailure(trans('messages.receita.vencida'));
            }
        }

        return $this->responseSuccess(true);
    }
}