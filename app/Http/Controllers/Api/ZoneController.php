<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ZoneResource;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zones = Zone::all();
        return response()->json([
            "data" => ZoneResource::collection($zones)
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
        $zone = Zone::create($request->all());
        if ($zone) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل منطقة جديدة",
                "data" => new ZoneResource($zone)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل منطقة جديدة ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show(Zone $zone)
    {
        return response()->json([
            "data" => ZoneResource::collection($zone)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zone $zone)
    {
        if ($zone->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل المنطقة",
                "data" => new ZoneResource($zone)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل المنطقة ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zone $zone)
    {
        if ($zone->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم حذف المنطقة",
                "data" => new ZoneResource($zone)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف المنطقة ",
            ], 422);
        }
    }
}
