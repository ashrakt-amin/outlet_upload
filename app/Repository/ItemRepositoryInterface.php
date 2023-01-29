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
     * Method for items conditions where column name
     */
    public function itemsWhereColumnName(array $attributes);

    /**
     * Method for items conditions where boolean name
     */
    public function itemsWhereBooleanName(array $attributes);

    /**
     * Method for items conditions where parent category id
     */
    public function itemsWhereCategoryParentId(array $attributes);

    /**
     * Method for items conditions where category id
     */
    public function itemsWhereCategoryId(array $attributes);

    /**
     * Method for items searching where key words
     */
    public function itemsSearchingWherekeyWords(array $attributes);

    /**
     * Method for items where unit boolean column
     */
    public function itemsWhereUnitBooleanColumn(array $attributes);

    /**
     * Method for items where discount
     */
    public function itemsWhereDiscount(array $attributes);

    /**
     * Method for items where discount
     */
    public function itemsLatest(array $attributes);

    /**
     * Method for all items conditions functional
     */
    public function itemsForAllConditions(array $attributes);

    /**
     * Method for all items conditions to latest
     */
    public function itemsForAllConditionsLatest(array $attributes, $resourceCollection);

    /**
     * Method for all items conditions to random
     */
    public function itemsForAllConditionsRandom(array $attributes, $resourceCollection);

    /**
     * Method for all items conditions to paginate
     */
    public function itemsForAllConditionsPaginate(array $attributes, $resourceCollection);

    /**
     * Method for all items conditions to return a wich method filtered by attributes
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
    public function toggleUpdate($id, $booleanName);
}
