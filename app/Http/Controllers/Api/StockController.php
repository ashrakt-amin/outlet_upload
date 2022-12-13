<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StockResource;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::with(['item'])->get();
        return response()->json([
            'data' => StockResource::collection($stocks)
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
        $stock = Stock::where([
            'item_id'   =>$request->item_id,
            'trader_id' =>$request->trader_id,
            'color_id'  =>$request->color_id,
            'size_id'   =>$request->size_id,
            'volume_id' =>$request->volume_id,
            'season_id' =>$request->season_id,
            ])->first();

        if ($stock) {
            $stock->stock = $stock->stock + $request->stock;
            $stock->update();

            return response()->json([
                "success" => true,
                "message" => "تم زيادة رصيد المنتج",
                "data" => new StockResource($stock)
            ], 200);
        } else {
            $item = Item::find($request->item_id);
            $stock = new Stock();
            $stock->fill($request->input());
            $stock->stock_code = $item->code.$request->color_id.$request->size_id;
            if ($stock->save()) {
                return response()->json([
                    "success" => true,
                    "message" => "تم اضافة رصيدا جديدا للمنتج",
                    "data" => new StockResource($stock)
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
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        $stock = Stock::where(['id' => $stock->id])->with(['item'])->first();
        return response()->json([
            'data' => new StockResource($stock)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        $existStock = Stock::where('id', '!=', $stock->id)->where([
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
        if ($stock->update($request->all())) {
            return response()->json([
                "success" => true,
                "message" => "تم تعديل الرصيد للمنتج",
                "data" => new StockResource($stock)
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
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        if ($stock->delete()) {
            return response()->json([
                "success" => true,
                "message" => "تم حذف الرصيد للمنتج",
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "فشل  حذف الرصيد للمنتج",
            ], 422);
        }
    }
}
