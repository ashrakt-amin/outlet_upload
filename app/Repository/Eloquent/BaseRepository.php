<?php

namespace App\Repository\Eloquent;

use App\Repository\EloquentRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
     protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

   /**
    * @return Collection
    */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
    * @param array $attributes
    *
    * @return Model
    */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

   /**
    * @param id $attributes
    * @return Model
    */
    public function edit($id, array $attributes) {
        $data = $this->model->findOrFail($id);
        $data->update($attributes);
        return $data;
    }

    /**
    * @param $id
    * @return response
    */
    public function delete($id): ?Model
    {
        $data = $this->model->findOrFail($id);
        $data->delete();
        return $data;
    }
}
