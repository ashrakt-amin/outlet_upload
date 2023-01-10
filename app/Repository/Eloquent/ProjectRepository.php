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
    * CategoryRepository constructor.
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
            $mainProject->name = "مولات";
            $mainProject->save();
            $mainProject = new MainProject();
            $mainProject->name = "مناطق";
            $mainProject->save();
        }
        return $this->model->all();
    }

    /**
    * @param array $attributes
    *
    * @return project
    */
    public function create(array $attributes): project
    {
        $project = $this->model->create($attributes);
        $project->projectImages()->createMany($this->setImages($attributes['img'], 'projects', 'img',450, 450));
        return $project;
    }

    /**
    * @param $id
    * @return project
    */
    public function find($id): ?project
    {
        return Project::where(['id'=>$id])->with(['levels', 'projectImages'])->first();
    }
}
