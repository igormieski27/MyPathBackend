<?php

namespace App\Repositories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Collection;

class ItemRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(Item::class);
    }

    public function listar(): Collection
    {
        return $this->model
            ->select('*')
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