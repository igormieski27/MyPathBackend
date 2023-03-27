<?php

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\UsuarioRepository;
use App\Services\BaseService;
use App\Repositories\UsuarioTarefaRepository;
use App\Repositories\UsuarioItemRepository;
use App\Repositories\TarefaRepository;
use App\Repositories\ItemRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioService extends BaseService
{
    private $usuarioRepository;
    protected UsuarioTarefaRepository $usuarioTarefaRepository;
    protected UsuarioItemRepository $usuarioItemRepository;
    protected ItemRepository $itemRepository;
    protected TarefaRepository $tarefaRepository;

    public function __construct(
        Request $request,
        UsuarioRepository $usuarioRepository,
        UsuarioTarefaRepository $usuarioTarefaRepository,
        UsuarioItemRepository $usuarioItemRepository,
        ItemRepository $itemRepository,
        TarefaRepository $tarefaRepository
    )
    {
        parent::__construct($request);

        $this->usuarioRepository = $usuarioRepository;
        $this->usuarioTarefaRepository = $usuarioTarefaRepository;
        $this->usuarioItemRepository = $usuarioItemRepository;
        $this->itemRepository = $itemRepository;
    }

    public function logar($body)
    {
        $usuario = $this->usuarioRepository->carregarPorEmail($body['email']);

        if (!$usuario) {
            return $this->responseNotFound(trans('messages.auth.invalido'));
        }

        if (!Hash::check(base64_decode($body['senha']), $usuario->senha)) {
            return $this->responseNotFound(trans('messages.auth.invalido'));
        }
        
        return $this->responseSuccess([
            'usuario' => $usuario,
            'token' => $usuario->createToken("API TOKEN")->plainTextToken
        ]);
    }

    public function save($body)
    {
        try {
            DB::beginTransaction();
            $usuario;
            if (!isset($body['id'])) { // CADASTRO
                $emailExiste = $this->usuarioRepository->validarEmail($body['email']);
                if ($emailExiste) {
                    return $this->responseNotAcceptable(trans('messages.auth.email_invalido'));
                }

                $body['senha'] = bcrypt(base64_decode($body['senha']));
                $usuario = $this->usuarioRepository->create($body);
            } else { // EDIÇÃO
                $usuario = $this->usuarioRepository->findOneById($body['id']);
                if (!$usuario) {
                    return $this->responseNotFound(trans('messages.usuario.nao_localiado'));
                }

                $usuario->fill($body);
            }
            $this->usuarioRepository->save($usuario);
            DB::commit();
            
            return $this->responseCreated(trans(isset($body['id']) ? 'messages.usuario.alterado' : 'messages.auth.cadastrado'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e);
        }
    }

    public function deslogar($request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->responseSuccess('Usuário desconectado');
    }

    public function findOne(string $id)
    {
        $usuario =  $this->usuarioRepository->findOneById($id);
        $tarefas = $this->usuarioTarefaRepository->findByIdUsuario($id);

        return $this->responseSuccess(['usuario' => $usuario]);
    }

    // public function addTask(string $idTarefa, string $idUsuario)
    // {
    //     $tarefas = $this->tarefaRepository->findOneById($idUsuario);

    //     return $this->responseSuccess(['usuario' => $usuario, 'tarefas' => $tarefas ]);
    // }
    public function buscarAtividadeSemanal(string $id)
    {
        $atividade = DB::select(DB::raw("
        SELECT COALESCE(count(data_conclusao), 0) as count
        FROM generate_series(NOW()::date - interval '6 days', NOW(), '1 day'::interval) as dates
        LEFT JOIN (
            SELECT data_conclusao
            FROM usuario_tarefa 
            WHERE data_conclusao BETWEEN (NOW() - interval '7 days') AND NOW()
            AND id_usuario = 2
        ) AS atividadeSemanal
        ON dates::date = atividadeSemanal.data_conclusao::date
        GROUP BY dates::date
        ORDER BY dates::date;
        "));
        
        return $this->responseSuccess($atividade);
    }

    public function comprarItem($body)
    {
        try {
            DB::beginTransaction();
            // pegando o usuário p/ pegar o saldo de gold dele
            $Usuario = $this->usuarioRepository->findOneById($body['id_usuario']);
            // pegando o item p/ pegar o valor do item
            $Item = $this->itemRepository->findOneById($body['id_item']);
            // subtraindo o valor do item pelo valor do gold 
            $Usuario['gold'] = $Usuario['gold'] - $Item['valor'];
            // commita
            $usuario = $this->usuarioItemRepository->create($body);
            $this->usuarioItemRepository->save($usuario);
            $this->usuarioRepository->save($Usuario);
            DB::commit();
            return $this->responseCreated(trans('messages.usuario.comprado'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailure($e->getMessage());
        }   
    }
}

