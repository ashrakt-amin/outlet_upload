<?php

namespace App\Http\Controllers\Api;

use App\Models\Importer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImporterResource;
use App\Http\Resources\ImporterCollection;

class ImporterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $importer = Importer::all();
        return response()->json([
            "data" => new ImporterCollection($importer)
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
        $importer = Importer::create($request->all());
        if ($importer) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل شركة مستورده جديدة",
                "data" => $importer
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل شركة مستورده جديدة ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Importer  $importer
     * @return \Illuminate\Http\Response
     */
    public function show(Importer $importer)
    {
        return response()->json([
            "success" => true,
            "data" => new ImporterResource($importer)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Importer  $importer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Importer $importer)
    {
        if ($importer->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الشركة المستورده",
                "data" => $importer
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الشركة المستورده ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Importer  $importer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Importer $importer)
    {
        if ($importer->items->count() == 0) {
            if ($importer->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف الشركة المستورده",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف الشركة المستورده ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف شركة مستورده لديها منتجات",
            ], 422);
        }
    }
}
