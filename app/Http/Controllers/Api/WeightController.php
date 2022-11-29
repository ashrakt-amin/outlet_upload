<?php

namespace App\Http\Controllers\Api;

use App\Models\Weight;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WeightCollection;
use App\Http\Resources\WeightResource;
use App\Models\Item;

class WeightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $weights = Weight::all();
        return response()->json([
            "data" => WeightResource::collection($weights)
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
        $weight = Weight::create($request->all());
        if ($weight) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل وزنا جديدا",
                "data" => $weight
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل وزنا جديدا ",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Weight  $weight
     * @return \Illuminate\Http\Response
     */
    public function show(Weight $weight)
    {
        return response()->json([
            "data" => new WeightResource($weight)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Weight  $weight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Weight $weight)
    {
        if ($weight->update(($request->all()))) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الوزن ",
                "data" => $weight
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل الوزن  ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Weight  $weight
     * @return \Illuminate\Http\Response
     */
    public function destroy(Weight $weight)
    {
        if ($weight->items->count() == 0) {
            if ($weight->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف الوزن ",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف الوزن  ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "لا يمكن حذف وزنا مرتبطا بمنتجات",
            ], 422);
        }
    }
}
