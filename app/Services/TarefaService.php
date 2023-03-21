<?php

namespace App\Services;

use Illuminate\Http\Request;

use App\Repositories\TarefaRepository;
use App\Repositories\UsuarioTarefaRepository;
use App\Repositories\UsuarioRepository;
use Illuminate\Support\Facades\DB;

class TarefaService extends BaseService
{
    protected TarefaRepository $repository;
    protected UsuarioTarefaRepository $usuarioTarefaRepository;
    protected UsuarioRepository $usuarioRepository;
    public function __construct(
        Request $request,
        TarefaRepository $repository,
        UsuarioTarefaRepository $usuarioTarefaRepository,
        UsuarioRepository $usuarioRepository,
    )
    {
        parent::__construct($request);

        $this->repository = $repository;
        $this->usuarioTarefaRepository = $usuarioTarefaRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    public function listarTarefas(string $idUsuario)
    {
        $Tarefas = $this->repository->listar($idUsuario);

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


    // Métodos UsuarioTarefa

    public function findByIdUsuario(string $id){
        $tarefas = $this->usuarioTarefaRepository->findByIdUsuario($id);
        return $this->responseSuccess(['tarefas' => $tarefas]);
    }


    public function concluirTarefa(array $body) // $body = usuarioTarefa
    {
        try {
            $idTarefa = $body['id_tarefa'];
            $tarefaConcluida = $this->usuarioTarefaRepository->findByIds($body['id_usuario'], $body['id_tarefa']);
            $usuario = $this->usuarioRepository->findOneById($body['id_usuario']);
            $tarefa = $this->repository->findOneById($idTarefa);
            if (!isset($tarefaConcluida)) {
                return $this->response(trans('messages.tarefa.nao_localizada'), 404);
            }
            $usuario['gold'] = $usuario['gold'] + $tarefa['reward_gold'];
            $usuario['xp'] = $usuario['xp'] + $tarefa['reward_exp'];
            $tarefaConcluida['status'] = $body['status'];
            $tarefaConcluida['data_conclusao'] = date("Y-m-d h:i:sa");
            
            DB::beginTransaction();
            $this->usuarioTarefaRepository->save($tarefaConcluida);
            $this->usuarioRepository->save($usuario);
            DB::commit();

            return $this->response(trans('messages.tarefa.alterada'), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e->getMessage());
        }
    }

    public function vincularTarefa(array $body){
        try{
            $Tarefa = $this->usuarioTarefaRepository->create($body);
            $Tarefa->fill($body);
            DB::beginTransaction();
            $this->usuarioTarefaRepository->save($Tarefa);
            DB::commit();

            return $this->response(trans('messages.tarefa.vinculada'), 200);
        }catch (\Exception $e){
            DB::rollBack();
            return $this->responseFailure($e->getMessage());
        }
    }    
}