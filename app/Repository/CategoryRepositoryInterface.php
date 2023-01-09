<?php
namespace App\Repository;

interface CategoryRepositoryInterface
{
    public function categoriesOfProject($project_id);
}
