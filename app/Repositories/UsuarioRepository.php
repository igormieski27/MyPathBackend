<?php

namespace App\Repositories;

use App\Models\Usuario;
use App\Repositories\BaseRepository;

class UsuarioRepository extends BaseRepository
{
    function __construct()
    {
        $this->model = $this->resolveModel(Usuario::class);
    }

    public function validarLogin($email, $senha): ?Usuario
    {
        return $this->model
            ->select('*')
            ->where('email', $email)
            ->where('senha', $senha)
            ->first();
    }
}
