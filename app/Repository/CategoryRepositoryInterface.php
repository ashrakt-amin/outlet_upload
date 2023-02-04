<?php
namespace App\Repository;

interface CategoryRepositoryInterface
{
   /**
    * @param array $attributes
    * @return Collection
    */
    public function categroiesForAllConditions(array $attributes);

    public function categoriesOfProject($project_id);
}
