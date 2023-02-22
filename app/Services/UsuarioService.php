<?php

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\UsuarioRepository;
use App\Services\BaseService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioService extends BaseService
{
    private $usuarioRepository;

    public function __construct(
        Request $request,
        UsuarioRepository $usuarioRepository
    )
    {
        parent::__construct($request);

        $this->usuarioRepository = $usuarioRepository;
    }

    public function logar($body)
    {
        $usuario = $this->usuarioRepository->validarLogin($body['email'], $body['senha']);

        if (!$usuario) {
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

        return $this->responseSuccess(['usuario' => $usuario ]);
    }
}
