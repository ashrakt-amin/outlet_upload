<?php

namespace App\Repository\Eloquent;

use App\Models\Project;
use App\Models\MainProject;
use Illuminate\Support\Collection;
use App\Repository\ProjectRepositoryInterface;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    use TraitImageProccessingTrait;
   /**
    * ProjectRepository constructor.
    *
    * @param Project $model
    */
   public function __construct(Project $model)
   {
       parent::__construct($model);
   }

   /**
    * @return Collection
    */
    public function all(): Collection
    {
        $mainProjects = MainProject::paginate();
        if (!count($mainProjects)) {
            $mainProject = new MainProject();
            $mainProject->name = "مناطق";
            $mainProject->save();
            $mainProject = new MainProject();
            $mainProject->name = "مولات";
            $mainProject->save();
        }
        return $this->model->all();
    }

    /**
    * @param array $attributes
    *
    * @return Project
    */
    public function create(array $attributes): Project
    {
        $project = $this->model->create($attributes);
        !array_key_exists('img', $attributes) ?
            $project->projectImages()->createMany($this->setImages($attributes['img'], 'projects', 'img',450, 450))
            : '';
        return $project;
    }

    /**
    * @param $id
    * @return Project
    */
    public function find($id): ?Project
    {
        return $this->model->with(['levels', 'projectImages', 'categories'])->find($id);
    }
}
