<?php
namespace App\Repository;

use App\Models\Item;

/**
* Interface ItemRepositoryInterface
* @package App\Repositories
*/
interface ItemRepositoryInterface
{
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

    // /**
    //  * @param id $attributes
    //  * @return Item
    //  */
    //  public function toggleUpdate($id, $booleanName);

    // /**
    //  * Method for items where discount
    //  */
    // public function itemsLatest(array $attributes);

    /**
     * Method for all items conditions to return a wich method filtered by attributes
     */
    // public function forAllConditionsReturn(array $attributes, $resourceCollection);
}
