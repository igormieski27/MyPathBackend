<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseRepository extends DB
{
    protected Model $model;

    /**
     * Inicia uma query do model
     */
    public function createQueryBuilder(): Builder
    {
        return $this->model->query()->newQuery();
    }

    /**
     * Cria uma instancia do model
     */
    public function create(array $data): Model
    {
        return new $this->model($data);
    }

    /**
     * Salva e retorna o resultado do save
     */
    public function save(Model &$model): bool
    {
        return $model->save();
    }

    /**
     * Atualiza ou cadastra um novo registro
     */
    public function updateOrCreate(array $where, array $data): mixed
    {
        return $this->model->updateOrCreate($where, $data);
    }

    /**
     * Deleta um registro
     */
    public function delete(mixed $param): bool|null
    {
        if (is_int($param)) {
            return $this->model->where('id', $param)->delete();
        }

        if ($param instanceof $this->model) {
            return $param->delete();
        }

        return false;
    }

    /**
     * Resolve a instância do model
     */
    protected function resolveModel($class): mixed
    {
        return app($class);
    }
}