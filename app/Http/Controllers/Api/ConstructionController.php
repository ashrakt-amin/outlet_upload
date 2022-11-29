<?php

namespace App\Http\Controllers\Api;
use App\Models\Construction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConstructionResource;
use App\Http\Resources\ConstructionCollection;

class ConstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $constructions = Construction::all();
        return response()->json([
            "data" => new ConstructionCollection($constructions)
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
        $construction = Construction::create($request->all());
        if ($construction) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل مبنا جديدا",
                "data" => new ConstructionResource($construction)
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
     * @param  \App\Models\Construction  $construction
     * @return \Illuminate\Http\Response
     */
    public function show(Construction $construction)
    {
        return response()->json([
        "data"=> new ConstructionResource($construction),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Construction  $construction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Construction $construction)
    {
        if ($construction->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل المبنى",
                "data" => new ConstructionResource($construction)
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
     * @param  \App\Models\Construction  $construction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Construction $construction)
    {
        if ($construction->levels->count() == 0) {
            if ($construction->delete()) {
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
