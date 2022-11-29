<?php

namespace App\Http\Controllers\Api;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResource;
use App\Http\Resources\ColorCollection;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Color::all();
        return response()->json([
            'data' => new ColorCollection($colors)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $color = Color::create($request->all());
        if ($color) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل لونا جديدا",
                "data" => new ColorResource($color)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل لونا جديدا",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(Color $color)
    {
        return response()->json([
            "success" => true,
            "data" => new ColorResource($color)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Color $color)
    {
        if ($color->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل اللون ",
                "data" => new ColorResource($color)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل اللون ",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        if ($color->colorSizeStocks->count() == 0) {
            if ($color->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف اللون ",
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف اللون ",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف لونا داخل منتجات",
            ], 422);
        }
    }
}
