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

//    /**
//     * @param array $attributes
//     * @return Model
//     */
//    public function create(array $attributes): Model;

//    /**
//     * @param $id
//     * @return Model
//     */
//    public function find($id): ?Model;

//    /**
//     * @param id $attributes
//     * @return Model
//     */
//     public function edit($id, array $attributes);

//     /**
//     * @param $id
//     * @return response
//     */
//     public function delete($id): ?Model;
}
