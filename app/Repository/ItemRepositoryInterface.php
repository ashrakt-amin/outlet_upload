<?php
namespace App\Repository;


use Illuminate\Support\Collection;
use App\Models\Item;

/**
* Interface ItemRepositoryInterface
* @package App\Repositories
*/
interface ItemRepositoryInterface
{
    /**
     * @return Collection
     */
     public function search(array $attributes);

    /**
     * @return Collection
     */
     public function latest(array $attributes);

    /**
     * @return Collection
     */
    public function offerItemsOfCategoriesOfProject($project_id, $category_id): Collection;

    /**
     * Method for all items conditions
     */
    public function itemsForAllConditions(array $attributes);

    /**
     * Method for all items conditions to random
     */
    public function itemsForAllConditionsRandom(array $attributes);

    /**
     * Method for all items conditions to paginate
     */
    public function itemsForAllConditionsPaginate(array $attributes);

    /**
     * Method for all items conditions to return a random or paginated array
     */
    public function itemsForAllConditionsReturn(array $attributes, $resourceCollection);

   /**
    * @param array $attributes
    * @return Item
    */
   public function create(array $attributes): Item;

   /**
    * @param $id
    * @return Item
    */
   public function find($id): ?Item;

   /**
    * @param id $attributes
    * @return Item
    */
    public function edit($id, array $attributes);

   /**
    * @param id $attributes
    * @return Item
    */
    public function toggleUpdate($id);
}
