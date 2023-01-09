<?php

namespace App\Http\Controllers\Api;
use App\Models\Project;

use App\Models\MainProject;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\NdProjectResource;
use App\Http\Resources\ProjectResource;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;
use App\Models\Category;

class ProjectController extends Controller
{
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainProjects = MainProject::paginate();
        if (count($mainProjects) < 1 ) {
            $mainProject = new MainProject();
            $mainProject->name = "مناطق";
            $mainProject->save();
            $mainProject = new MainProject();
            $mainProject->name = "مولات";
            $mainProject->save();
        }
        $projects = Project::paginate();
        return response()->json([
            "data" => NdProjectResource::collection($projects)
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function streetsOffers()
    {
        $projects = Project::where(['main_project_id' => 2])->paginate();
        return response()->json([
            "data" => ProjectResource::collection($projects)
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mallsOffers()
    {
        $categories = Category::select(
            "categories.*",
            "levels.id as level_id",
            "projects.name as project_name",
            "projects.id as project_id",
            // "mainProjects.id as main_project_id"
        )
        ->join("levels", ["levels.project_id" => "project_id"])
        ->join("projects", ["project_id" => "projects.id"])
        ->where(["projects.id" => 1])
        ->distinct('categories.id')->get()
        ->toArray();
        ;



        // $projects = Project::select(
        //     "projects.id",
        //     "projects.name",
        //     "categories.id as category_id",
        //     "categories.name as category_name",
        //         )
        //         ->join("levels", "levels.project_id", "=", "projects.id")
        //         ->join("units", "units.level_id", "=", "levels.id")
        //         ->join("category_unit", "category_unit.unit_id", "=", "units.id")
        //         ->join("categories", "category_id", "=", "category_unit.category_id")
        //         ->where('categories.parent_id', '<', 1)
        //         ->where('main_project_id', '=', 1)
        //         ->distinct('id')->get()
        //         ->toArray();

        return response()->json([
            "data" => ($categories)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
                        'name' => [
                            'required',
                            Rule::unique('projects', 'name')
                            ->ignore(request('Project'), 'id')
                        ],
                    ]);
        if ($validate) {
            if ($project = Project::create($request->all())) {
                if ($request->has('img')) {
                    foreach ($request->file('img') as $img) {
                        $image = new ProjectImage();
                        $image->project_id = $project->id;
                        $image->img = $this->setImage($img, 'projects', 450, 450);
                        $image->save();
                    }
                }
                return response()->json([
                    "success" => true,
                    "message" => "تم تسجيل مشروعا جديدا",
                    "data" => new ProjectResource($project),
                ], 200);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل المشروع",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $project = Project::where(['id'=>$project->id])->with(['levels', 'projectImages'])->first();
        return response()->json([
            "data"=> new NdProjectResource($project),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name'             => 'required',
            'main_project_id' => 'required',
        ]);
        if ($project->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل المشروع",
                "data" => new ProjectResource($project),
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل المشروع",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {

        if ($project->levels->count() == 0) {
            $project->delete();
            return response()->json([
                "success" => true,
                "message" => "تم حذف المشروع",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "المشروع به وحدات ولا يمكن الحذف",
            ], 422);
        }
    }
}
