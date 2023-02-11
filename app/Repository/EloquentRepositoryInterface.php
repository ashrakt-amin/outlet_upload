<?php
namespace App\Repository;


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
* Interface EloquentRepositoryInterface
* @package App\Repositories
*/
interface EloquentRepositoryInterface
{
    /**
     * @return Collection
     */
     public function all(): Collection;

   /**
    * @param array $attributes
    * @return Model
    */
   public function create(array $attributes): Model;

   /**
    * @param $id
    * @return Model
    */
   public function find($id): ?Model;

   /**
    * @param id $attributes
    * @return Model
    */
    public function edit($id, array $attributes);

    /**
    * @param $id
    * @return response
    */
    public function delete($id): ?Model;

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function restore($id);

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function restoreAll();

    /**
     * @param id $attributes
     * @return Model
     */
     public function toggleUpdate($id, $booleanName);


    /**
     * Method for all data conditions to return a wich method filtered by attributes
     */
    public function forAllConditionsReturn(array $attributes, $resourceCollection);
}
