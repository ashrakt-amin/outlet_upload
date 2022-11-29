<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ColorSizeStockResource;
use App\Models\ColorSizeStock;
use App\Models\Item;
use Illuminate\Http\Request;

class ColorSizeStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colorSizeStocks = ColorSizeStock::with(['item'])->get();
        return response()->json([
            'data' => ColorSizeStockResource::collection($colorSizeStocks)
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
        $colorSizeStock = ColorSizeStock::where([
            'item_id'   =>$request->item_id,
            'trader_id' =>$request->trader_id,
            'color_id'  =>$request->color_id,
            'size_id'   =>$request->size_id,
            'weight_id' =>$request->weight_id,
            'volume_id' =>$request->volume_id,
            'season_id' =>$request->season_id,
            ])->first();
            
        if ($colorSizeStock) {
            $colorSizeStock->stock = $colorSizeStock->stock + $request->stock;
            $colorSizeStock->update();

            return response()->json([
                "success" => true,
                "message" => "تم زيادة رصيد المنتج",
                "data" => new ColorSizeStockResource($colorSizeStock)
            ], 200);
        } else {
        $colorSizeStock = ColorSizeStock::create($request->all());
            if ($colorSizeStock) {
                return response()->json([
                    "success" => true,
                    "message" => "تم اضافة رصيدا جديدا للمنتج",
                    "data" => new ColorSizeStockResource($colorSizeStock)
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "فشل اضافة رصيدا جديدا للمنتج",
                ], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ColorSizeStock  $colorSizeStock
     * @return \Illuminate\Http\Response
     */
    public function show(ColorSizeStock $colorSizeStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ColorSizeStock  $colorSizeStock
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if ($request->color_id && $request->size_id) {
            $colorSizeStock = ColorSizeStock::where([
                'item_id'=>$request->item_id,
                'color_id'=>$request->color_id,
                'size_id'=>$request->size_id,
            ])->first();
            if ($colorSizeStock->stock >= $request->stock) {
                return response()->json([
                    "success" => true,
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "الرصيد المتاح".$colorSizeStock->stock,
                ], 422);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ColorSizeStock  $colorSizeStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ColorSizeStock $colorSizeStock)
    {
        $existStock = ColorSizeStock::where('id', '!=', $colorSizeStock->id)->where([
            'item_id'   =>$request->item_id,
            'trader_id' =>$request->trader_id,
            'color_id'  =>$request->color_id,
            'size_id'   =>$request->size_id,
            'weight_id' =>$request->weight_id,
            'volume_id' =>$request->volume_id,
            'season_id' =>$request->season_id,
            ])->count();

            if ($existStock > 0 ) {
                return response()->json([
                    "success" => true,
                    "message" => "ستوك موجود مسبقا",
                ], 200);
            }
        if ($colorSizeStock->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الرصيد للمنتج",
                "data" => new ColorSizeStockResource($colorSizeStock)
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل  تعديل الرصيد للمنتج",
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ColorSizeStock  $colorSizeStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(ColorSizeStock $colorSizeStock)
    {
        // if ($colorSizeStock->delete()) {
        //     return response()->json([
        //         "success" => true,
        //         "message" => "تم تعديل الرصيد للمنتج",
        //         "data" => new ColorSizeStockResource($colorSizeStock)
        //     ], 200);
        // } else {
        //     return response()->json([
        //         "success" => false,
        //         "message" => "فشل  تعديل الرصيد للمنتج",
        //     ], 422);
        // }
    }
}
