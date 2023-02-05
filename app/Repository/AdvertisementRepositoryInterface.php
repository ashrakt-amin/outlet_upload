<?php
namespace App\Repository;

use App\Models\Advertisement;
use Illuminate\Support\Collection;

interface AdvertisementRepositoryInterface
{
    // /**
    //  * @return Collection
    //  */
    //  public function all(): Collection;

    /**
     * Method for advertisements where column name
     */
    public function advertisementsWhereColumnName(array $attributes);

    /**
     *  Method for advertisements where all conditions
     */
    public function advertisementsWhereallConditions(array $attributes);

    /**
    * @param array $attributes
    *
    * @return Advertisement
    */
    public function create(array $attributes): Advertisement;

    /**
     * @param id $attributes
     * @return Advertisement
     */
    public function edit($id, array $attributes);
}
