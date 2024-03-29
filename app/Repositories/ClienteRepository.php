<?php

namespace App\Repositories;

use App\Models\Cliente;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class ClienteRepository extends BaseRepository
{
    function __construct()
    {
        $this->model = $this->resolveModel(Cliente::class);
    }

    public function findAll(): Collection
    {
        return $this->model
            ->select('*')
            ->get();
    }

    public function combo(): Collection
    {
        return $this->model
            ->select('id', 'nome')
            ->get();
    }

    public function findOneById(string $id)
    {
        return $this->model
            ->select('*')
            ->where('id', $id)
            ->first();
    }
}
