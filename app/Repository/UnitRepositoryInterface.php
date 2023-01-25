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
    public function toggleUpdate($id, array $attributes);

    /**
     * @return Collection
     */
    public function unitsForAllConditions(array $attributes);

    /**
     * Method for all units conditions to paginate
     */
    public function unitsForAllConditionsPaginate(array $attributes);

    /**
     * Method for all units conditions to random
     */
    public function unitsForAllConditionsRandom(array $attributes);

    /**
     * Method for all units conditions to return a random or paginated array
     */
    public function unitsForAllConditionsReturn(array $attributes, $resourceCollection);
}
