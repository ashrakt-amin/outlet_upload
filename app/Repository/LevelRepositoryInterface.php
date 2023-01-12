<?php
namespace App\Repository;

use App\Models\Level;
use Illuminate\Support\Collection;

interface LevelRepositoryInterface {

    /**
    * @param $id
    * @return Level
    */
    public function find($id): ?Level;
}
