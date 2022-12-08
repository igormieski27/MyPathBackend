<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

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

    public function logar($body) {
        $usuario = $this->usuarioRepository->validarLogin($body['email'], $body['senha']);

        if (!$usuario) {
            return $this->responseNotFound(trans('messages.auth.invalido'));
        }

        return $this->responseSuccess($usuario);
    }
}
