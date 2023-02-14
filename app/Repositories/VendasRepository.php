<?php

namespace App\Repositories;

use App\Models\Vendas;

class VendasRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(Vendas::class);
    }
}