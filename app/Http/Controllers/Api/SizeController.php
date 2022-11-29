<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SizeResource;
use App\Http\Resources\SizeCollection;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Size::all();
        return response()->json([
            'data' => new SizeCollection($sizes)
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
        $size = Size::create($request->all());
        if ($size) {
            return response()->json([
                "success" => true,
                "message" => "تم تسجيل مقاسا جديدا",
                "data" => new SizeResource($size)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تسجيل مقاسا جديدا",
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        return response()->json([
            "success" => true,
            "data" => new SizeResource($size)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Size $size)
    {
        if ($size->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل المقاس",
                "data" => new SizeResource($size)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل تعديل المقاس",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
    {
        if ($size->colorSizeStocks->count() == 0) {
            if ($size->delete()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم حذف المقاس",
                    "data" => new SizeResource($size)
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل حذف المقاس",
                ], 422);
            }
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل حذف نوعا داخل منتجات",
            ], 422);
        }
    }
}
