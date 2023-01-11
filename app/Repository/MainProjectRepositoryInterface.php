<?php
namespace App\Repository;

use App\Models\MainProject;
use Illuminate\Support\Collection;

interface MainProjectRepositoryInterface {

    public function all(): Collection;

    /**
    * @param $id
    * @return MainProject
    */
    public function find($id): ?MainProject;
}
