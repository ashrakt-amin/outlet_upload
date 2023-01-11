<?php
namespace App\Repository;

use Illuminate\Support\Collection;
interface MainProjectRepositoryInterface {

    public function all(): Collection;
}
