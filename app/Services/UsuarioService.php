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

        $token = $usuario->createToken($usuario->email)->plainTextToken;

        return $this->responseSuccess([
            'usuario' => $usuario,
            'token' => $token
        ]);
    }

    public function save($body)
    {
        $emailExiste = $this->usuarioRepository->validarEmail($body['email']);
        if ($emailExiste) {
            return $this->responseNotAcceptable(trans('messages.auth.email_invalido'));
        }


        $data = $body;
        $data['senha'] = bcrypt(base64_decode($body['senha']));
        try {
            DB::beginTransaction();

            $usuario = $this->usuarioRepository->create($body);
            $this->usuarioRepository->save($usuario);

            DB::commit();
            return $this->responseCreated(trans( 'messages.auth.cadastrado'));
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

    public function carregarCombo()
    {
        $funcionarios = $this->usuarioRepository->comboFuncionarios();

        return $this->responseSuccess($funcionarios);
    }
}
