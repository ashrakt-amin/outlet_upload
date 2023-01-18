<?php
namespace App\Repository;

use App\Models\MainProject;
use Illuminate\Support\Collection;

interface MainProjectRepositoryInterface {

   /**
    * @return Collection
    */
    public function all(): Collection;

    // /**
    // * @param $id
    // * @return MainProject
    // */
    // public function find($id): ?MainProject;
}
