<?php
namespace App\Repository;


use Illuminate\Support\Collection;
use App\Models\Unit;

/**
* Interface UnitRepositoryInterface
* @package App\Repositories
*/
interface UnitRepositoryInterface
{
    /**
     * @return Collection
     */
     public function all(): Collection;

    /**
     * @return Collection
     */
     public function latest(): Collection;

   /**
    * @param array $attributes
    * @return Unit
    */
   public function create(array $attributes): Unit;

   /**
    * @param $id
    * @return Unit
    */
   public function find($id): ?Unit;

   /**
    * @param id $attributes
    * @return Unit
    */
    public function edit($id, array $attributes);

    /**
     * @param id $attributes
     * @return Unit
     */
    public function toggleUpdate($id);

    /**
     * @return Collection
     */
    public function famous(array $attributes): Collection;
}
