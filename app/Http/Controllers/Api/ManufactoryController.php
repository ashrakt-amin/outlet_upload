<?php

namespace App\Http\Controllers\Api;
use App\Models\Manufactory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ManufactoryCollection;
use App\Http\Resources\ManufactoryResource;

class ManufactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manufactories = Manufactory::all();
        return response()->json([
            "data" => new ManufactoryCollection($manufactories)
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
        $importer = Manufactory::create($request->all());
        if ($importer) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل شركة مصنعة جديدة",
                "data" => $importer
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل شركة مصنعة جديدة ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manufactory  $manufactory
     * @return \Illuminate\Http\Response
     */
    public function show(Manufactory $manufactory)
    {
        return response()->json([
            "data" => new ManufactoryResource($manufactory)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Manufactory  $manufactory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manufactory $manufactory)
    {
        if ($manufactory->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الشركة المصنعة ",
                "data" => $manufactory
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الشركة المصنعة  ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manufactory  $manufactory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufactory $manufactory)
    {
        if ($manufactory->items->count() == 0) {
            if ($manufactory->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف الشركة المصنعة",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف الشركة المصنعة ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف شركة مصنعة لديها منتجات",
            ], 422);
        }
    }
}
