<?php

namespace App\Repositories;

use App\Models\Usuario;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class UsuarioRepository extends BaseRepository
{
    function __construct()
    {
        $this->model = $this->resolveModel(Usuario::class);
    }

    public function carregarPorEmail($email): ?Usuario
    {
        return $this->model
            ->select('*')
            ->where('email', $email)
            ->first();
    }

    public function validarEmail($email): ?Usuario
    {
        return $this->model
            ->select('*')
            ->where('email', $email)
            ->first();
    }

    public function findOneById(string $id)
    {
        return $this->model
            ->select('*')
            ->where('id', $id)
            ->first();
    }
}
