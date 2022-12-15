<?php

namespace App\Http\Controllers\Api;
use App\Models\Project;

use App\Models\EskanCompany;
use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

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
        $projects = Project::all();
        return response()->json([
            "data" => ProjectResource::collection($projects)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $eskanCompanies = EskanCompany::all();
        if (count($eskanCompanies) < 1 ) {
            $eskanCompany = new EskanCompany();
            $eskanCompany->name = "شركة اسكان المنصورة";
            $eskanCompany->save();
        }
        $validate = $request->validate([
                        'name' => [
                            'required',
                            Rule::unique('projects', 'name')
                            ->ignore(request('Project'), 'id')
                        ],
                    ]);
        if ($validate) {
            $project = Project::create($request->all());
            if ($project) {
                if ($request->hasFile('img')) {
                    foreach ($request->file('img') as $img) {
                        $originalFilename = $this->setImage($img, $project->id, 'lg');
                        $filename = $this->aspectForResize($img, $project->id, 450, 450, 'sm');
                        $image = new ProjectImage();
                        $image->project_id = $project->id;
                        $image->img        = $filename;
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
        $project = Project::where(['id'=>$project->id])->with('levels', 'projectImages')->first();
        return response()->json([
            "data"=> ($project),
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
            'eskan_company_id' => 'required',
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
