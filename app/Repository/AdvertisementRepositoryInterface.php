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
     * @return Collection
     */
    public function advertisementsWhereColumnName(array $attributes);

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
