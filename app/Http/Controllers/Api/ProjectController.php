<?php

namespace App\Http\Controllers\Api;
use App\Models\Project;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;

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
                    "data" => $project
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
        $project = Project::where('id', $project->id)->with(['constructions','units', 'levels'])->get();
        return response()->json([
            "data"=> ProjectResource::collection($project),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
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
            'name'        => 'required',
        ]);
        if ($project->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل المشروع",
                "data" => $project
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

        if ($project->units->count() == 0) {
            $project->delete();
            return response()->json([
                "success" => true,
                "message" => "تم حذف المشروع",
                "data" => $project
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "المشروع به وحدات ولا يمكن الحذف",
            ], 422);
        }
    }
}
