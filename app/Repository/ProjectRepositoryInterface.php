<?php
namespace App\Repository;
use App\Models\Project;
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface {

   /**
    * @return Collection
    */
    public function all(): Collection;

   /**
    * @param array $attributes
    * @return Project
    */
    public function create(array $attributes): Project;

    /**
    * @param $id
    * @return project
    */
    public function find($id): ?project;
 }
