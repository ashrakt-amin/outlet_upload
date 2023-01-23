<?php
namespace App\Repository;

use Illuminate\Support\Collection;

interface AdvertisementRepositoryInterface
{
    /**
     * @return Collection
     */
     public function all(): Collection;
}
