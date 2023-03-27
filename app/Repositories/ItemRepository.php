<?php

namespace App\Repositories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ItemRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(Item::class);
    }

    public function listar(string $idUsuario): Array
    {
        return DB::select(DB::raw("
        SELECT i.* FROM public.items i
        where i.id not in (select id_item from public.usuario_item where id_usuario = :idUsuario)
        "), ['idUsuario' => $idUsuario]);
        
    }

    public function findOneById(string $id)
    {
        return $this->model
            ->select('*')
            ->where('id', $id)
            ->first();
    }
    
}