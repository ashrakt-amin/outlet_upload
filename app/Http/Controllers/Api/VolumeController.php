<?php

namespace App\Http\Controllers\Api;

use App\Models\Volume;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VolumeResource;

class VolumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $volumes = Volume::all();
        return response()->json([
            "data" => VolumeResource::collection($volumes)
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
        $volume = Volume::create($request->all());
        if ($volume) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل كمية جديدة",
                "data" => $volume
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل كمية جديدة ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Http\Response
     */
    public function show(Volume $volume)
    {
        return response()->json([
            "success" => true,
            "data" => $volume
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Volume $volume)
    {
        if ($volume->update(($request->all()))) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الكمية ",
                "data" => $volume
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الكمية  ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Http\Response
     */
    public function destroy(Volume $volume)
    {
        if ($volume->items->count() == 0) {
            if ($volume->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف الكمية ",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف الكمية  ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "لا يمكن حذف كمية مرتبطة بمنتجات",
            ], 422);
        }
    }
}
