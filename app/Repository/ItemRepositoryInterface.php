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
     public function latest(): Collection;

    /**
     * @return Collection
     */
    public function random(): Collection;

    /**
     * @return Collection
     */
    public function offerItemsOfCategoriesOfProject($project_id, $category_id): Collection;

    /**
     * @return Collection
     */
    public function itemsForAllConditions(array $attributes): Collection;

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
