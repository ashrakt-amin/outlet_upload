<?php

namespace App\Http\Controllers\Api;
use App\Models\MainProject;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MainProjectResource;

class MainProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainProjects = MainProject::all();
        return response()->json([
            "data" => MainProjectResource::collection($mainProjects)
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
        $mainProject = MainProject::create($request->all());
        if ($mainProject) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل مبنا جديدا",
                "data" => new MainProjectResource($mainProject)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل المبنى",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainProject  $mainProject
     * @return \Illuminate\Http\Response
     */
    public function show(MainProject $mainProject)
    {
        $mainProject = MainProject::where(['id'=>$mainProject->id])->with(['projects'])->first();
        return response()->json([
        "data"=> new MainProjectResource($mainProject),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MainProject  $mainProject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,MainProject $mainProject)
    {
        if ($mainProject->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل المبنى",
                "data" => new MainProjectResource($mainProject)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل المبنى",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainProject  $mainProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(MainProject $mainProject)
    {
        if ($mainProject->levels->count() == 0) {
            if ($mainProject->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف المبنى ",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف المبنى",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف مبنا به ادوار",
            ], 422);
        }
    }
}
