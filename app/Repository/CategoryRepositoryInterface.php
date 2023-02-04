<?php
namespace App\Repository;

interface CategoryRepositoryInterface
{
   /**
    * @param array $attributes
    * @return Collection
    */
    public function categroiesForAllConditions(array $attributes);

    /**
     * Get categories of a project.
     *
     * @return Collection
     */
    public function categoriesOfProject($project_id);
}
