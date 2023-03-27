<?php

namespace App\Repositories;

use App\Models\UsuarioItem;
use Illuminate\Database\Eloquent\Collection;

class UsuarioItemRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = $this->resolveModel(UsuarioItem::class);
    }

    public function findByIdUsuario(string $id)
    {
        return $this->model
            ->select('usuario_item.*', 'items.*')
            ->join('items', 'items.id', '=', 'usuario_item.id_item')
            ->where('id_usuario', $id)
            ->get();
    }

    public function findByIds(string $idUsuario, string $idItem)
    {
        return $this->model
            ->select('usuario_item.*')
            ->join('items', 'items.id', '=', 'usuario_item.id_item')
            ->where('id_usuario', $idUsuario)
            ->where('id_item', $idItem)
            ->first();
    }
    

}