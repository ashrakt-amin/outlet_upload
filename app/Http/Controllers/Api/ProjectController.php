<?php

namespace App\Http\Controllers\Api;
use App\Models\Project;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\EskanCompany;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        // return new Collection($projects);
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
        
        $project = Project::where(['id'=>$project->id])->with('levels')->first();
        return response()->json([
            "data"=> new ProjectResource($project),
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

        if ($project->levels()->count() == 0) {
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
